<?php

use App\Models\User;
use App\Notifications\VerifyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('shows teh registration screen', function () {
    // expect(true)->toBeTrue();
    $response = $this->get(route('register'));

    $response->assertOk();

    $response->assertStatus(200);

    $response->assertSee('Crear Cuenta');
    $response->assertSee('Registrarme');

    $response->assertSeeInOrder([
        'Crear Cuenta',
        'Registrarme'
    ]);
});


it('registers a new user as unverified and dispatches the registered event', function(){

    Event::fake();

    $response = $this->post(route('register.store'),[
        'name' => 'Juan Pérez',
        'email' => 'juan@juan.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect(route('verification.notice'));

    $user = User::where('email', 'juan@juan.com')->first();

    expect($user)->not->toBeNull();
    expect($user->name)->toBe('Juan Pérez');
    expect($user->email)->toBe('juan@juan.com');
    expect($user->hasVerifiedEmail())->toBeFalse();

    Event::assertDispatched(Registered::class);

});

it('should validate required fields when the request body is empty', function(){
    $response = $this->post(route('register.store'), [ ]);

    $response->assertSessionHasErrors([
        'name' => 'El nombre es obligatorio',
        'email' => 'El email es obligatorio',
        'password' => 'La contraseña es obligatoria'
        ]);
});

it('prevents duplicate emails address', function(){

    User::factory()->create([
        'email' => 'juan@juan.com',
    ]);

    $response = $this->post(route('register.store'),[
        'name' => 'Juan Pérez',
        'email' => 'juan@juan.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertRedirect();

    $response->assertSessionHasErrors([
        'email' => 'El email ya esta registrado'
    ]);
});

it('sends the verification email notifications after registration', function () {

    Notification::fake();

    $response = $this->post(route('register.store'), [
        'name' => 'Juan Pérez',
        'email' => 'juan@juan.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);


    $user = User::where('email', 'juan@juan.com')->first();

    Notification::assertSentTo($user, VerifyEmail::class);

});

it('verifies the user email form a signed verification link', function(){

    $user = User::factory()->unverified()->create();

    // dd($user);

    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->email),
        ]
    );

    // simula la acción del usuario al hacer clic en el enlace de verificación
    $response = $this->actingAs($user)->get($verificationUrl);

     $response->assertRedirect(route('dashboard'));

     expect($user->hasVerifiedEmail())->toBeTrue();

});

it('does not allo an unverified user to access the dashboard', function () {
    $user = User::factory()->unverified()->create();

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertRedirect(route('verification.notice'));

});


it('allows a verifed user to access the dashboard', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();

});



