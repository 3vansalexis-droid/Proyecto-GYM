# Instrucciones del proyecto

## Tecnologías

- Aplicación Laravel 13 con PHP 8.3 o superior.
- Usa las convenciones estándar de Laravel para rutas, controladores, modelos, migraciones, vistas y comandos Artisan.
- El frontend se compila con Vite y utiliza Bootstrap 5, Bootstrap Icons, Tailwind CSS y Sass según corresponda al módulo.
- Mantén la separación entre la lógica de negocio, la capa HTTP y las vistas.

## Desarrollo

- Ejecuta `composer install` y `npm install` para instalar dependencias.
- Usa `php artisan` para tareas de Laravel.
- Ejecuta `npm run build` para verificar la compilación del frontend.
- Ejecuta `composer test` o `php artisan test` para correr las pruebas.
- Usa `vendor/bin/pint` para aplicar el formato de código PHP del proyecto.

## Convenciones

- Antes de modificar una funcionalidad, revisa las implementaciones relacionadas y reutiliza los patrones existentes.
- Valida los datos de entrada con Form Requests cuando la validación no sea trivial.
- Protege las operaciones que cambian datos con autorización y validación apropiadas.
- Crea o actualiza pruebas para cambios de comportamiento.
- No incluyas secretos ni valores específicos del entorno en el código; usa `.env` y actualiza `.env.example` cuando corresponda.
- Mantén los cambios enfocados y documenta únicamente decisiones o comportamientos no evidentes.

# Contexto del Proyecto: Sistema de Control de Gimnasio y Membresías

## Stack Tecnológico y Arquitectura
- Framework: Laravel (MVC monolítico) con PHP 8.4.
- Base de datos: PostgreSQL (motor `pgsql`).
- ORM: Eloquent (todos los modelos DEBEN declarar explícitamente `protected $table = 'nombre_tabla';` y `protected $fillable`).
- Frontend: Vistas Blade con Bootstrap 5 compilado mediante Vite (interfaz limpia, moderna, con soporte para modo oscuro `data-bs-theme="dark"`).
- Idioma del código y base de datos: Español para tablas, columnas, rutas y vistas.

## Esquema de Base de Datos (10 Tablas Exactas)
Respeta estrictamente este orden de jerarquía por llaves foráneas:
1. `roles`: id, nombre (unique), descripcion.
2. `usuarios`: id, rol_id (FK -> roles), nombre, email (unique), password_hash, activo (boolean), creado_en.
3. `socios`: id, clave_socio (unique), nombre, apellidos, telefono, email, fecha_nacimiento, fecha_registro, activo (boolean).
4. `tipos_membresia`: id, nombre (unique), duracion_dias, precio (decimal 10,2), activo (boolean).
5. `suscripciones`: id, socio_id (FK -> socios), tipo_membresia_id (FK -> tipos_membresia), fecha_inicio, fecha_fin, estado ('activa', 'vencida', 'cancelada').
6. `pagos`: id, suscripcion_id (FK -> suscripciones), usuario_id (FK -> usuarios), monto (decimal 10,2), metodo_pago ('efectivo', 'tarjeta_debito', 'tarjeta_credito', 'transferencia'), fecha_pago.
7. `asistencias`: id, socio_id (FK -> socios), usuario_id (FK -> usuarios), fecha_hora.
8. `entrenadores`: id, nombre, apellidos, telefono, especialidad, activo (boolean).
9. `clases`: id, entrenador_id (FK -> entrenadores), nombre, dia_semana, hora_inicio, hora_fin, cupo_maximo.
10. `inscripcion_clases`: id, clase_id (FK -> clases), socio_id (FK -> socios), fecha_inscripcion. Restricción UNIQUE compuesta en (clase_id, socio_id).

## Reglas de Generación de Código
- No uses CDN para Bootstrap; impórtalo a través de `resources/css/app.css` y `resources/js/app.js` con `@vite`.
- En migraciones, usa `$table->foreignId(...)` con `constrained('nombre_tabla')`.
- En controladores, mantén métodos RESTful (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) y valida datos antes de guardar.
