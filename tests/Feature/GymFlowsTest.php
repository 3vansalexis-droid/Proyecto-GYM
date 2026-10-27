<?php

namespace Tests\Feature;

use App\Models\Clase;
use App\Models\Entrenador;
use App\Models\InscripcionClase;
use App\Models\Rol;
use App\Models\Socio;
use App\Models\Suscripcion;
use App\Models\TipoMembresia;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GymFlowsTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_users_are_redirected_to_their_allowed_start_page(): void
    {
        $admin = $this->createUser('Administrador', 'admin@example.test');
        $trainer = $this->createUser('Entrenador', 'trainer@example.test');

        $this->post(route('login.attempt'), [
            'email' => $admin->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('asistencias.create'));

        $this->post(route('logout'))->assertRedirect(route('login'));

        $this->post(route('login.attempt'), [
            'email' => $trainer->email,
            'password' => 'Password123!',
        ])->assertRedirect(route('dashboard'));
    }

    public function test_inactive_users_cannot_log_in(): void
    {
        $user = $this->createUser('Recepcionista', 'inactive@example.test', false);

        $this->from(route('login'))
            ->post(route('login.attempt'), [
                'email' => $user->email,
                'password' => 'Password123!',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors(['email' => 'Tu cuenta está desactivada. Contacta al administrador.']);
    }

    public function test_roles_are_enforced_by_route(): void
    {
        $receptionist = $this->createUser('Recepcionista', 'reception@example.test');
        $trainer = $this->createUser('Entrenador', 'trainer@example.test');
        $admin = $this->createUser('Administrador', 'admin@example.test');

        $this->actingAs($receptionist)
            ->get(route('usuarios.index'))
            ->assertForbidden();

        $this->actingAs($trainer)
            ->get(route('asistencias.create'))
            ->assertForbidden();

        $this->actingAs($admin)
            ->get(route('usuarios.index'))
            ->assertOk();
    }

    public function test_reception_records_attendance_only_for_a_member_with_a_current_subscription(): void
    {
        $receptionist = $this->createUser('Recepcionista', 'reception@example.test');
        $member = $this->createMember('SOC-001');
        $type = $this->createMembershipType();

        $this->actingAs($receptionist)
            ->from(route('asistencias.create'))
            ->post(route('asistencias.store'), ['clave_socio' => $member->clave_socio])
            ->assertRedirect(route('asistencias.create'))
            ->assertSessionHas('error', 'El socio no tiene una suscripción activa y vigente.');

        $this->createSubscription($member, $type);

        $this->actingAs($receptionist)
            ->post(route('asistencias.store'), ['clave_socio' => $member->clave_socio])
            ->assertRedirect(route('asistencias.create'))
            ->assertSessionHas('success', 'Asistencia registrada correctamente.');

        $this->assertDatabaseHas('asistencias', [
            'socio_id' => $member->id,
            'usuario_id' => $receptionist->id,
        ]);
    }

    public function test_creating_a_subscription_creates_its_payment_atomically(): void
    {
        $receptionist = $this->createUser('Recepcionista', 'reception@example.test');
        $member = $this->createMember('SOC-002');
        $type = $this->createMembershipType();

        $this->actingAs($receptionist)
            ->post(route('suscripciones.store'), [
                'socio_id' => $member->id,
                'tipo_membresia_id' => $type->id,
                'fecha_inicio' => today()->toDateString(),
                'metodo_pago' => 'efectivo',
            ])
            ->assertRedirect(route('suscripciones.index'));

        $subscription = Suscripcion::firstOrFail();

        $this->assertDatabaseHas('suscripciones', [
            'id' => $subscription->id,
            'socio_id' => $member->id,
            'estado' => 'activa',
        ]);
        $this->assertDatabaseHas('pagos', [
            'suscripcion_id' => $subscription->id,
            'usuario_id' => $receptionist->id,
            'monto' => '50.00',
        ]);
    }

    public function test_class_enrollment_requires_a_current_membership_and_respects_capacity(): void
    {
        $admin = $this->createUser('Administrador', 'admin@example.test');
        $member = $this->createMember('SOC-003');
        $secondMember = $this->createMember('SOC-006');
        $thirdMember = $this->createMember('SOC-007');
        $type = $this->createMembershipType();
        $this->createSubscription($secondMember, $type);
        $this->createSubscription($thirdMember, $type);
        $trainer = Entrenador::create([
            'nombre' => 'Ana',
            'apellidos' => 'Entrenadora',
            'activo' => true,
        ]);
        $class = Clase::create([
            'entrenador_id' => $trainer->id,
            'nombre' => 'Funcional',
            'dia_semana' => 'Lunes',
            'hora_inicio' => '08:00',
            'hora_fin' => '09:00',
            'cupo_maximo' => 2,
        ]);

        $this->actingAs($admin)
            ->post(route('inscripciones.store'), [
                'clase_id' => $class->id,
                'socio_id' => $member->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'El socio debe tener una membresía activa y vigente.');

        $this->createSubscription($member, $type);

        $this->actingAs($admin)
            ->post(route('inscripciones.store'), [
                'clase_id' => $class->id,
                'socio_id' => $member->id,
            ])
            ->assertRedirect(route('inscripciones.index'))
            ->assertSessionHas('success', 'Socio inscrito correctamente en la clase.');

        $this->actingAs($admin)
            ->post(route('inscripciones.store'), [
                'clase_id' => $class->id,
                'socio_id' => $member->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'El socio ya está inscrito en esta clase.');

        $this->actingAs($admin)
            ->post(route('inscripciones.store'), [
                'clase_id' => $class->id,
                'socio_id' => $secondMember->id,
            ])
            ->assertRedirect(route('inscripciones.index'))
            ->assertSessionHas('success', 'Socio inscrito correctamente en la clase.');

        $this->actingAs($admin)
            ->post(route('inscripciones.store'), [
                'clase_id' => $class->id,
                'socio_id' => $thirdMember->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('error', 'No hay cupo disponible en esta clase.');

            $this->assertDatabaseCount('inscripcion_clases', 2);
    }

    public function test_class_capacity_cannot_be_reduced_below_existing_enrollments(): void
    {
        $admin = $this->createUser('Administrador', 'admin@example.test');
        $member = $this->createMember('SOC-004');
        $secondMember = $this->createMember('SOC-005');
        $type = $this->createMembershipType();
        $this->createSubscription($member, $type);
        $this->createSubscription($secondMember, $type);
        $trainer = Entrenador::create([
            'nombre' => 'Luis',
            'apellidos' => 'Entrenador',
            'activo' => true,
        ]);
        $class = Clase::create([
            'entrenador_id' => $trainer->id,
            'nombre' => 'Yoga',
            'dia_semana' => 'Martes',
            'hora_inicio' => '08:00',
            'hora_fin' => '09:00',
            'cupo_maximo' => 3,
        ]);
        InscripcionClase::create([
            'clase_id' => $class->id,
            'socio_id' => $member->id,
        ]);
        InscripcionClase::create([
            'clase_id' => $class->id,
            'socio_id' => $secondMember->id,
        ]);

        $response = $this->actingAs($admin)->put(route('clases.update', $class), [
            'entrenador_id' => $trainer->id,
            'nombre' => 'Yoga',
            'dia_semana' => 'Martes',
            'hora_inicio' => '08:00',
            'hora_fin' => '09:00',
            'cupo_maximo' => 1,
        ]);

        $response->assertSessionHasErrors('cupo_maximo');
        $this->assertDatabaseHas('clases', [
            'id' => $class->id,
            'cupo_maximo' => 3,
        ]);
    }

    private function createUser(string $roleName, string $email, bool $active = true): Usuario
    {
        $role = Rol::firstOrCreate(['nombre' => $roleName]);

        return Usuario::create([
            'rol_id' => $role->id,
            'nombre' => $roleName,
            'email' => $email,
            'password_hash' => Hash::make('Password123!'),
            'activo' => $active,
        ]);
    }

    private function createMember(string $key): Socio
    {
        return Socio::create([
            'clave_socio' => $key,
            'nombre' => 'Socio',
            'apellidos' => 'De Prueba',
            'activo' => true,
        ]);
    }

    private function createMembershipType(): TipoMembresia
    {
        return TipoMembresia::create([
            'nombre' => 'Plan de prueba '.uniqid(),
            'duracion_dias' => 30,
            'precio' => 50,
            'activo' => true,
        ]);
    }

    private function createSubscription(Socio $member, TipoMembresia $type): Suscripcion
    {
        return Suscripcion::create([
            'socio_id' => $member->id,
            'tipo_membresia_id' => $type->id,
            'fecha_inicio' => today(),
            'fecha_fin' => Carbon::today()->addDays(30),
            'estado' => 'activa',
        ]);
    }
}
