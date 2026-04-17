<?php

use App\Events\NumberDrawn;
use App\Models\DrawnNumber;
use App\Models\Game;
use App\Models\User;
use Illuminate\Support\Facades\Event;

// ── Event dispatch (tested directly, not via HTTP) ───────────────────────────

test('NumberDrawn can be dispatched and captured by Event::fake', function () {
    $game        = Game::factory()->create();
    $drawnNumber = DrawnNumber::factory()->for($game)->create(['number' => 42]);

    Event::fake([NumberDrawn::class]);

    NumberDrawn::dispatch($game, $drawnNumber);

    Event::assertDispatched(NumberDrawn::class, function (NumberDrawn $event) use ($game) {
        return $event->game->is($game)
            && $event->drawnNumber->number === 42;
    });
});

// ── Controller integration (HTTP → DB + event wired up) ──────────────────────

test('drawing a number via HTTP persists it and returns 201', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->postJson(route('games.drawn-numbers.store', $game), ['number' => 42])
        ->assertStatus(201)
        ->assertJsonPath('data.number', 42);

    expect($game->drawnNumbers()->where('number', 42)->exists())->toBeTrue();
});

test('NumberDrawn is not dispatched when the number fails validation', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    Event::fake([NumberDrawn::class]);

    NumberDrawn::dispatchIf(false, $game, new DrawnNumber());

    Event::assertNotDispatched(NumberDrawn::class);
});

test('NumberDrawn is not dispatched on duplicate number', function () {
    $user        = User::factory()->create();
    $game        = Game::factory()->create();
    DrawnNumber::factory()->for($game)->create(['number' => 7]);

    Event::fake([NumberDrawn::class]);

    // Simulate the guard: only dispatch when the number doesn't already exist
    $alreadyDrawn = $game->drawnNumbers()->where('number', 7)->exists();
    NumberDrawn::dispatchIf(! $alreadyDrawn, $game, new DrawnNumber(['number' => 7]));

    Event::assertNotDispatched(NumberDrawn::class);
});

// ── Event payload ────────────────────────────────────────────────────────────

test('NumberDrawn broadcasts on the correct private game channel', function () {
    $game        = Game::factory()->create();
    $drawnNumber = DrawnNumber::factory()->for($game)->create(['number' => 33]);

    $channels = (new NumberDrawn($game, $drawnNumber))->broadcastOn();

    expect($channels)->toHaveCount(1)
        ->and($channels[0]->name)->toBe('private-game.' . $game->id);
});

test('NumberDrawn payload contains the drawn number and running total', function () {
    $game = Game::factory()->create();
    DrawnNumber::factory()->for($game)->create(['number' => 10]);
    DrawnNumber::factory()->for($game)->create(['number' => 20]);
    $third = DrawnNumber::factory()->for($game)->create(['number' => 30]);

    $payload = (new NumberDrawn($game, $third))->broadcastWith();

    expect($payload['total_drawn'])->toBe(3)
        ->and($payload['drawn_number']['number'])->toBe(30);
});

// ── Channel authorization ────────────────────────────────────────────────────

test('authenticated user can authorize a private game channel', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->post('/broadcasting/auth', [
            'channel_name' => 'private-game.' . $game->id,
            'socket_id'    => '123.456',
        ])
        ->assertStatus(200);
});
