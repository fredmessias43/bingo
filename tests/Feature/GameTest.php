<?php

use App\Models\Game;
use App\Models\User;

function validGamePayload(array $overrides = []): array
{
    return array_merge([
        'name'        => 'Test Game',
        'max_players' => '10',
        'is_free'     => 'true',
        'entry_fee'   => '0',
        'status'      => 'active',
        'game_data'   => ['start_datetime' => now()->addDay()->toDateTimeString()],
    ], $overrides);
}

test('guests are redirected to login for all game routes', function () {
    $game = Game::factory()->create();

    $this->get(route('games.index'))->assertRedirect('/login');
    $this->get(route('games.create'))->assertRedirect('/login');
    $this->post(route('games.store'))->assertRedirect('/login');
    $this->get(route('games.show', $game))->assertRedirect('/login');
    $this->get(route('games.edit', $game))->assertRedirect('/login');
    $this->put(route('games.update', $game))->assertRedirect('/login');
    $this->delete(route('games.destroy', $game))->assertRedirect('/login');
});

test('authenticated user can list games', function () {
    $user = User::factory()->create();
    Game::factory()->count(3)->create();

    $this->actingAs($user)
        ->get(route('games.index'))
        ->assertStatus(200);

    expect(Game::count())->toBe(3);
});

test('create form loads with game modes', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('games.create'))
        ->assertStatus(200);
});

test('authenticated user can create a game', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('games.store'), validGamePayload())
        ->assertRedirect();

    expect(Game::count())->toBe(1)
        ->and(Game::first()->name)->toBe('Test Game');
});

test('game creation fails validation with missing required fields', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('games.store'), [])
        ->assertSessionHasErrors(['name', 'max_players']);

    expect(Game::count())->toBe(0);
});

test('authenticated user can view a game', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->get(route('games.show', $game))
        ->assertStatus(200);
});

test('edit form loads with existing game data', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->get(route('games.edit', $game))
        ->assertStatus(200);
});

test('authenticated user can update a game', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->put(route('games.update', $game), validGamePayload(['name' => 'Updated Name']))
        ->assertRedirect(route('games.index'));

    expect($game->fresh()->name)->toBe('Updated Name');
});

test('game update fails validation with invalid data', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->put(route('games.update', $game), ['name' => ''])
        ->assertSessionHasErrors(['name']);
});

test('authenticated user can soft-delete a game', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->delete(route('games.destroy', $game))
        ->assertRedirect(route('games.index'));

    expect(Game::count())->toBe(0)
        ->and(Game::withTrashed()->count())->toBe(1);
});
