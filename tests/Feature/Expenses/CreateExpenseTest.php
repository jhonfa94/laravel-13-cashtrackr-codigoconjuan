<?php

use App\Models\Budget;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);


it('allows the budget owner to create an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
  
    $budget = Budget::factory()->for($user)->create([
        'type' => 'general',
    ]);

    $response = $this->actingAs($user)->post(route('expenses.store', $budget),[
        'name' => 'Dentista',
        'amount' => 300,
        'category' => 'healt'
    ]);
   
    $response->assertRedirect(route('budgets.show', $budget));
    $response->assertSessionHas('success', 'Gasto creado correctamente');

    $this->assertDatabaseHas('expenses', [
        'name' => 'Dentista',
        'amount' => 300,
        'category' => 'healt',
        'budget_id' => $budget->id,
    ]);

   

    
});

it('allows the budget owner to create an expense in a goal budget without category', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('does not allow guests to create expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('does not allow unverified users to create expenses', function () {
    $user = User::factory()->create([
        'email_verified_at' => null,
    ]);
});

it('does not allow other users to create expenses in someone else budget', function () {
    $owner = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $otherUser = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('validates required fields when creating an expense in a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('validates category must be valid for a general budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});

it('does not require category for a goal budget', function () {
    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);
});
