<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Rol extends Model
{
    public const CATALOGO_PERMISOS = [
        'recepcion' => [
            'titulo' => 'Recepción y Asistencias',
            'descripcion' => 'Registrar entradas (Check-in) y consultar historial de asistencias.',
            'icono' => 'bi-upc-scan',
            'grupo' => 'Operación',
        ],
        'socios' => [
            'titulo' => 'Socios',
            'descripcion' => 'Registrar, consultar, editar y dar de baja socios del gimnasio.',
            'icono' => 'bi-people-fill',
            'grupo' => 'Operación',
        ],
        'suscripciones' => [
            'titulo' => 'Suscripciones',
            'descripcion' => 'Asignar, renovar y gestionar membresías de los socios.',
            'icono' => 'bi-calendar-plus',
            'grupo' => 'Operación',
        ],
        'pagos' => [
            'titulo' => 'Pagos y Caja',
            'descripcion' => 'Registrar cobros de membresías y consultar recibos de pago.',
            'icono' => 'bi-cash-coin',
            'grupo' => 'Operación',
        ],
        'clases' => [
            'titulo' => 'Clases Grupales',
            'descripcion' => 'Crear, editar y administrar horarios y cupos de clases.',
            'icono' => 'bi-calendar-event',
            'grupo' => 'Entrenamiento',
        ],
        'inscripciones' => [
            'titulo' => 'Inscripciones a Clases',
            'descripcion' => 'Inscribir socios con membresía activa en clases y cancelar lugares.',
            'icono' => 'bi-person-plus',
            'grupo' => 'Entrenamiento',
        ],
        'entrenadores' => [
            'titulo' => 'Entrenadores',
            'descripcion' => 'Administrar el catálogo de entrenadores e instructores.',
            'icono' => 'bi-person-workspace',
            'grupo' => 'Administración',
        ],
        'tipos_membresia' => [
            'titulo' => 'Tipos de Membresía',
            'descripcion' => 'Configurar planes de membresía, precios y duraciones en días.',
            'icono' => 'bi-card-checklist',
            'grupo' => 'Administración',
        ],
        'administracion' => [
            'titulo' => 'Seguridad, Usuarios y Roles',
            'descripcion' => 'Administrar cuentas de usuario, roles, anular pagos y corregir asistencias.',
            'icono' => 'bi-shield-lock-fill',
            'grupo' => 'Administración',
        ],
    ];

    protected $table = 'roles';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'permisos',
    ];

    protected function casts(): array
    {
        return [
            'permisos' => 'array',
        ];
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(Usuario::class, 'rol_id');
    }

    /**
     * Devuelve la lista de claves de permisos activos para este rol.
     *
     * @return list<string>
     */
    public function permisosEfectivos(): array
    {
        if (mb_strtolower(trim((string) $this->nombre)) === 'administrador') {
            return array_keys(self::CATALOGO_PERMISOS);
        }

        $guardadosEnColumna = $this->getAttribute('permisos');
        if (is_array($guardadosEnColumna)) {
            return array_values(array_intersect($guardadosEnColumna, array_keys(self::CATALOGO_PERMISOS)));
        }

        $mapaArchivo = self::leerMapaPermisosArchivo();
        if ($this->id && array_key_exists((string) $this->id, $mapaArchivo)) {
            return array_values(array_intersect((array) $mapaArchivo[(string) $this->id], array_keys(self::CATALOGO_PERMISOS)));
        }

        return self::permisosPorDefectoSegunNombre((string) $this->nombre);
    }

    /**
     * Indica si el rol cuenta con un permiso específico.
     */
    public function tienePermiso(string $permiso): bool
    {
        return in_array($permiso, $this->permisosEfectivos(), true);
    }

    /**
     * Guarda los permisos del rol tanto en columna DB (si existe) como en archivo de respaldo.
     *
     * @param  array<int, string>  $permisos
     */
    public function sincronizarPermisos(array $permisos): void
    {
        $limpios = mb_strtolower(trim((string) $this->nombre)) === 'administrador'
            ? array_keys(self::CATALOGO_PERMISOS)
            : array_values(array_intersect($permisos, array_keys(self::CATALOGO_PERMISOS)));

        try {
            if (Schema::hasColumn('roles', 'permisos')) {
                $this->forceFill(['permisos' => $limpios])->saveQuietly();
            }
        } catch (\Throwable) {
            // Si aún no se ha ejecutado la migración en la BD local, se usa el respaldo en storage.
        }

        if ($this->id) {
            $mapa = self::leerMapaPermisosArchivo();
            $mapa[(string) $this->id] = $limpios;
            self::guardarMapaPermisosArchivo($mapa);
        }
    }

    /**
     * Elimina los permisos almacenados en el respaldo cuando se borra un rol.
     */
    public function eliminarPermisosRespaldo(): void
    {
        if (! $this->id) {
            return;
        }

        $mapa = self::leerMapaPermisosArchivo();
        if (array_key_exists((string) $this->id, $mapa)) {
            unset($mapa[(string) $this->id]);
            self::guardarMapaPermisosArchivo($mapa);
        }
    }

    /**
     * Permisos predeterminados para los roles base del sistema.
     *
     * @return list<string>
     */
    public static function permisosPorDefectoSegunNombre(string $nombre): array
    {
        return match (mb_strtolower(trim($nombre))) {
            'administrador' => array_keys(self::CATALOGO_PERMISOS),
            'recepcionista' => ['recepcion', 'socios', 'suscripciones', 'pagos'],
            'entrenador' => ['clases'],
            default => [],
        };
    }

    /**
     * @return array<string, list<string>>
     */
    private static function leerMapaPermisosArchivo(): array
    {
        $ruta = storage_path('app/role_permissions.json');
        if (! is_file($ruta)) {
            return [];
        }

        $contenido = @file_get_contents($ruta);
        if (! is_string($contenido) || $contenido === '') {
            return [];
        }

        $datos = json_decode($contenido, true);

        return is_array($datos) ? $datos : [];
    }

    /**
     * @param  array<string, list<string>>  $mapa
     */
    private static function guardarMapaPermisosArchivo(array $mapa): void
    {
        $ruta = storage_path('app/role_permissions.json');
        $directorio = dirname($ruta);
        if (! is_dir($directorio)) {
            @mkdir($directorio, 0775, true);
        }

        @file_put_contents($ruta, json_encode($mapa, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
