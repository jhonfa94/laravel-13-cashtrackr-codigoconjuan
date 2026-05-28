<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

it('shows the login screen', function(){
    $response = $this->get(route('login'));

    $response->assertOk();

    $response->assertStatus(200);

    $response->assertSee('Iniciar Sesión');
});


it('logs in a verified user successfully ', function(){

    $user = User::factory()->create([
        'email' =>'juan@juan.com',
        'password' => 'password123',
        'email_verified_at' => now(),
    ]);

    $response = $this->post(route('login'), [
        'email' => 'juan@juan.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);

});

it('does not log in with invalid credentials ', function(){

    $user = User::factory()->create([
        'email' =>'juan@juan.com',
        'password' => 'password123',
        'email_verified_at' => now(),
    ]);

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => 'juan@juan.com',
        'password' => 'incorrectPassword',
    ]);

    $response->assertRedirect(route('login'));

    $response->assertSessionHas('error', 'Credenciales incorrectas');

    $this->assertGuest();

});


it('prevents unverified user from accessing dashboard', function () {
    $user = User::factory()->unverified()->create([
        'email' => 'juan@juan.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'juan@juan.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertAuthenticated();

    $dashboardResponse = $this->get(route('dashboard'));
    $dashboardResponse->assertRedirect(route('verification.notice'));
});

it('does not allow access to dashboard if email is not verified', function () {
    $user = User::factory()->unverified()->create(
       [ 'email_verified_at' => null]
    );

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertRedirect(route('verification.notice'));
});

it('allow access to dashboard if email is verified', function () {
    $user = User::factory()->unverified()->create(
       [ 'email_verified_at' => now()]
    );

    $response = $this->actingAs($user)->get(route('dashboard'));
    $response->assertOk();
});


it('fails login if user dows not exist', function () {
    $response  = $this->from(route('login'))
        ->post(route('login.store'), [
            'email' => 'emailtest@test.com',
            'password' => 'password123',
        ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHasErrors([
        'email' => 'El correo electrónico no existe.'
    ]);

    $this->assertGuest();
});
