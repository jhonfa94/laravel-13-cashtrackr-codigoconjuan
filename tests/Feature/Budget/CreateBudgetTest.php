<?php

use App\Models\Budget;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);

it('validates required fields when createing a budget', function (){

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
    ->from(route('budgets.create'))
    ->post(route('budgets.store'),[
        'name' => '',
        'amount' => '',
        'type' => '',
    ]);

    $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors([
        'name',
        'amount',
        'type' ,
    ]);

});

it('does not allow guest to create budgets', function (){
    $response = $this->post(route('budgets.store'), [
        'name' => 'Test Budget',
        'amount' => 100,
        'type' => 'income',
    ]);

    $response->assertRedirect(route('login'));
    $this->assertGuest();
});

it('assigns the created budget to the autenticated user', function (){

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user)->post(route('budgets.store'),[
        'name' => 'My Budget',
        'amount' => 1000,
        'type' => 'goal',
    ]);

    $this->assertDatabaseHas('budgets',[
        'name' => 'My Budget',
        'amount' => 1000,
        'type' => 'goal',
        'user_id' => $user->id,
    ]);

    $budget = Budget::first();


    expect($budget->user_id)->toBe($user->id);

});

it('creates a budget and redirects with success message', function () {

    /** 
     * @var \App\Models\User $user
     * @var \App\Models\Budget $budget
     * */
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('budgets.store'), [
        'name' => 'My Budget',
        'amount' => 1000,
        'type' => 'goal',
    ]);

    $budget = Budget::first();


    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success', 'Presupuesto creado correctamente');


});

it('does not allow unverified users to create budgets', function () {

    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);

    $response = $this->actingAs($user)->post(route('budgets.store'), [
        'name' => 'My Budget',
        'amount' => 1000,
        'type' => 'goal',
    ]);

    $response->assertRedirect(route('verification.notice'));


});


it('validates ammount must be greater than zero', function () {

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => -100,
            'type' => 'goal',
        ]);

    // $response->assertRedirect(route('budgets.create'));
    $response->assertSessionHasErrors([
        'amount',
    ]);


});

it('accept valid budget types', function () {

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)
        ->from(route('budgets.create'))
        ->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => 100,
            'type' => 'general',
        ]);


    $response->assertSessionHasErrors([
        'type',
    ]);


});

it('validate type be valid', function () {

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $response = $this->actingAs($user)->post(route('budgets.store'), [
            'name' => 'Boda',
            'amount' => 100,
            'type' => 'general',
        ]);


    $response->assertSessionDoesntHaveErrors();

    $this->assertDatabaseHas('budgets', [
        'type' => 'general',
    ]);
});
