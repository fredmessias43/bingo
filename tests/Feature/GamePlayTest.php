<?php

use App\Models\Card;
use App\Models\DrawnNumber;
use App\Models\Game;
use App\Models\Player;
use App\Models\User;

// Shared card layout used across tests:
//  [0]  [1]  [2]  [3]  [4]   →  1– 5
//  [5]  [6]  [7]  [8]  [9]   →  6–10
// [10] [11] [12] [13] [14]   → 11–15
// [15] [16] [17] [18] [19]   → 16–20
// [20] [21] [22] [23] [24]   → 21–25

// ── Drawing numbers ──────────────────────────────────────────────────────────

test('authenticated user can draw a number for a game', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)
        ->postJson(route('games.drawn-numbers.store', $game), ['number' => 42])
        ->assertStatus(201)
        ->assertJsonPath('data.number', 42);

    expect($game->drawnNumbers()->count())->toBe(1);
});

test('cannot draw the same number twice in the same game', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();
    DrawnNumber::factory()->for($game)->create(['number' => 42]);

    $this->actingAs($user)
        ->postJson(route('games.drawn-numbers.store', $game), ['number' => 42])
        ->assertStatus(422);

    expect($game->drawnNumbers()->count())->toBe(1);
});

test('the same number can be drawn in different games', function () {
    $user  = User::factory()->create();
    $game1 = Game::factory()->create();
    $game2 = Game::factory()->create();

    $this->actingAs($user)->postJson(route('games.drawn-numbers.store', $game1), ['number' => 7])->assertStatus(201);
    $this->actingAs($user)->postJson(route('games.drawn-numbers.store', $game2), ['number' => 7])->assertStatus(201);

    expect($game1->drawnNumbers()->count())->toBe(1)
        ->and($game2->drawnNumbers()->count())->toBe(1);
});

test('number must be between 1 and 75', function () {
    $user = User::factory()->create();
    $game = Game::factory()->create();

    $this->actingAs($user)->postJson(route('games.drawn-numbers.store', $game), ['number' => 0])->assertStatus(422);
    $this->actingAs($user)->postJson(route('games.drawn-numbers.store', $game), ['number' => 76])->assertStatus(422);
});

test('guests cannot draw numbers', function () {
    $game = Game::factory()->create();

    $this->postJson(route('games.drawn-numbers.store', $game), ['number' => 1])
        ->assertStatus(401);
});

// ── Listing drawn numbers ────────────────────────────────────────────────────

test('lists drawn numbers scoped to a game', function () {
    $user  = User::factory()->create();
    $game1 = Game::factory()->create();
    $game2 = Game::factory()->create();

    DrawnNumber::factory()->for($game1)->createMany([['number' => 1], ['number' => 2], ['number' => 3]]);
    DrawnNumber::factory()->for($game2)->create(['number' => 10]);

    $this->actingAs($user)
        ->getJson(route('games.drawn-numbers.index', $game1))
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

test('deleting a drawn number removes it from the game', function () {
    $user        = User::factory()->create();
    $game        = Game::factory()->create();
    $drawnNumber = DrawnNumber::factory()->for($game)->create(['number' => 33]);

    $this->actingAs($user)
        ->deleteJson(route('games.drawn-numbers.destroy', [$game, $drawnNumber]))
        ->assertStatus(200);

    expect($game->drawnNumbers()->count())->toBe(0);
});

// ── Win detection during gameplay ────────────────────────────────────────────

test('player wins with a complete row after numbers are drawn', function () {
    $game   = Game::factory()->create();
    $player = Player::factory()->for($game)->create();
    $card   = Card::factory()->for($game)->for($player)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 2], ['number' => 3], ['number' => 4], ['number' => 5],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();
    expect($card->hasWon($drawn, 'line'))->toBeTrue();
});

test('player does not win with an incomplete row', function () {
    $game   = Game::factory()->create();
    $player = Player::factory()->for($game)->create();
    $card   = Card::factory()->for($game)->for($player)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 2], ['number' => 3], ['number' => 4],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();
    expect($card->hasWon($drawn, 'line'))->toBeFalse();
});

test('player wins with a column', function () {
    $game   = Game::factory()->create();
    $player = Player::factory()->for($game)->create();
    $card   = Card::factory()->for($game)->for($player)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    // column 0: indices 0,5,10,15,20 → numbers 1,6,11,16,21
    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 6], ['number' => 11], ['number' => 16], ['number' => 21],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();
    expect($card->hasWon($drawn, 'column'))->toBeTrue();
});

test('player wins with the main diagonal', function () {
    $game   = Game::factory()->create();
    $player = Player::factory()->for($game)->create();
    $card   = Card::factory()->for($game)->for($player)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    // main diagonal: indices 0,6,12,18,24 → numbers 1,7,13,19,25
    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 7], ['number' => 13], ['number' => 19], ['number' => 25],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();
    expect($card->hasWon($drawn, 'diagonal'))->toBeTrue();
});

test('player wins with four corners', function () {
    $game   = Game::factory()->create();
    $player = Player::factory()->for($game)->create();
    $card   = Card::factory()->for($game)->for($player)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    // corners: indices 0,4,20,24 → numbers 1,5,21,25
    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 5], ['number' => 21], ['number' => 25],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();
    expect($card->hasWon($drawn, 'corners'))->toBeTrue();
});

test('player wins full card when all numbers drawn', function () {
    $game   = Game::factory()->create();
    $player = Player::factory()->for($game)->create();
    $numbers = range(1, 25);
    $card   = Card::factory()->for($game)->for($player)->create(['numbers' => $numbers]);

    foreach ($numbers as $n) {
        DrawnNumber::factory()->for($game)->create(['number' => $n]);
    }

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();
    expect($card->hasWon($drawn, 'full'))->toBeTrue();
});

// ── Multiple players ─────────────────────────────────────────────────────────

test('only the winning player card is detected as a winner', function () {
    $game    = Game::factory()->create();
    $player1 = Player::factory()->for($game)->create();
    $player2 = Player::factory()->for($game)->create();

    $winnerCard = Card::factory()->for($game)->for($player1)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    $loserCard = Card::factory()->for($game)->for($player2)->create(['numbers' => [
        26, 27, 28, 29, 30,
        31, 32, 33, 34, 35,
        36, 37, 38, 39, 40,
        41, 42, 43, 44, 45,
        46, 47, 48, 49, 50,
    ]]);

    // Draw row 0 of the winner card
    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 2], ['number' => 3], ['number' => 4], ['number' => 5],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();

    expect($winnerCard->hasWon($drawn, 'line'))->toBeTrue()
        ->and($loserCard->hasWon($drawn, 'line'))->toBeFalse();
});

test('all winning cards in a game can be found after drawing numbers', function () {
    $game    = Game::factory()->create();
    $player1 = Player::factory()->for($game)->create();
    $player2 = Player::factory()->for($game)->create();

    // Both players share the same first row numbers
    $card1 = Card::factory()->for($game)->for($player1)->create(['numbers' => [
         1,  2,  3,  4,  5,
         6,  7,  8,  9, 10,
        11, 12, 13, 14, 15,
        16, 17, 18, 19, 20,
        21, 22, 23, 24, 25,
    ]]);

    $card2 = Card::factory()->for($game)->for($player2)->create(['numbers' => [
         1,  2,  3,  4,  5,
        26, 27, 28, 29, 30,
        31, 32, 33, 34, 35,
        36, 37, 38, 39, 40,
        41, 42, 43, 44, 45,
    ]]);

    DrawnNumber::factory()->for($game)->createMany([
        ['number' => 1], ['number' => 2], ['number' => 3], ['number' => 4], ['number' => 5],
    ]);

    $drawn = $game->drawnNumbers()->pluck('number')->toArray();

    $winners = $game->cards->filter(fn(Card $card) => $card->hasWon($drawn, 'line'));

    expect($winners)->toHaveCount(2);
});
