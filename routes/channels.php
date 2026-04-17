<?php

use Illuminate\Support\Facades\Broadcast;

// Private channel per game — any authenticated user can join.
// Restrict to game participants here when player/invite logic is complete.
Broadcast::channel('game.{gameId}', function ($user, string $gameId) {
    return ['id' => $user->id, 'name' => $user->name];
});
