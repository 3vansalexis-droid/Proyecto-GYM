<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\Entrenador;
use App\Models\Rol;
use App\Models\Socio;
use App\Models\TipoMembresia;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = [];
        foreach ([
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso completo al sistema.'],
            ['nombre' => 'Recepcionista', 'descripcion' => 'Gestiona socios, pagos y membresías.'],
            ['nombre' => 'Entrenador', 'descripcion' => 'Gestiona clases y entrenamientos.'],
        ] as $datosRol) {
            $rol = Rol::updateOrCreate(
                ['nombre' => $datosRol['nombre']],
                $datosRol,
            );
            $roles[$rol->nombre] = $rol;
        }

        Usuario::updateOrCreate(
            ['email' => 'administrador@gym.test'],
            [
                'rol_id' => $roles['Administrador']->id,
                'nombre' => 'Administrador del Gimnasio',
                'password_hash' => Hash::make('password123'),
                'activo' => true,
            ],
        );

        Usuario::updateOrCreate(
            ['email' => 'recepcionista@gym.test'],
            [
                'rol_id' => $roles['Recepcionista']->id,
                'nombre' => 'Recepcionista del Gimnasio',
                'password_hash' => Hash::make('password123'),
                'activo' => true,
            ],
        );

        foreach ([
            ['nombre' => 'Mensual', 'duracion_dias' => 30, 'precio' => 500.00],
            ['nombre' => 'Trimestral', 'duracion_dias' => 90, 'precio' => 1350.00],
            ['nombre' => 'Anual', 'duracion_dias' => 365, 'precio' => 4800.00],
        ] as $datosMembresia) {
            TipoMembresia::updateOrCreate(
                ['nombre' => $datosMembresia['nombre']],
                [...$datosMembresia, 'activo' => true],
            );
        }

        $entrenadores = [];
        foreach ([
            [
                'nombre' => 'Carlos',
                'apellidos' => 'Ramírez López',
                'telefono' => '555-100-2001',
                'especialidad' => 'Musculación',
            ],
            [
                'nombre' => 'Laura',
                'apellidos' => 'Hernández García',
                'telefono' => '555-100-2002',
                'especialidad' => 'Entrenamiento funcional',
            ],
        ] as $datosEntrenador) {
            $entrenador = Entrenador::updateOrCreate(
                [
                    'nombre' => $datosEntrenador['nombre'],
                    'apellidos' => $datosEntrenador['apellidos'],
                ],
                [...$datosEntrenador, 'activo' => true],
            );
            $entrenadores[] = $entrenador;
        }

        Clase::updateOrCreate(
            ['nombre' => 'Spinning'],
            [
                'entrenador_id' => $entrenadores[0]->id,
                'dia_semana' => 'Lunes',
                'hora_inicio' => '07:00',
                'hora_fin' => '08:00',
                'cupo_maximo' => 20,
            ],
        );

        Clase::updateOrCreate(
            ['nombre' => 'Yoga'],
            [
                'entrenador_id' => $entrenadores[1]->id,
                'dia_semana' => 'Miércoles',
                'hora_inicio' => '18:00',
                'hora_fin' => '19:00',
                'cupo_maximo' => 15,
            ],
        );

        foreach ([
            ['clave_socio' => 'SOC-001', 'nombre' => 'Ana', 'apellidos' => 'Martínez Silva', 'telefono' => '555-200-3001', 'email' => 'ana.martinez@example.com'],
            ['clave_socio' => 'SOC-002', 'nombre' => 'Luis', 'apellidos' => 'Gómez Torres', 'telefono' => '555-200-3002', 'email' => 'luis.gomez@example.com'],
            ['clave_socio' => 'SOC-003', 'nombre' => 'Sofía', 'apellidos' => 'Pérez Castillo', 'telefono' => '555-200-3003', 'email' => 'sofia.perez@example.com'],
            ['clave_socio' => 'SOC-004', 'nombre' => 'Miguel', 'apellidos' => 'Fernández Ruiz', 'telefono' => '555-200-3004', 'email' => 'miguel.fernandez@example.com'],
            ['clave_socio' => 'SOC-005', 'nombre' => 'Valeria', 'apellidos' => 'Morales Díaz', 'telefono' => '555-200-3005', 'email' => 'valeria.morales@example.com'],
        ] as $datosSocio) {
            Socio::updateOrCreate(
                ['clave_socio' => $datosSocio['clave_socio']],
                [...$datosSocio, 'activo' => true],
            );
        }
    }
}
