<?php

namespace App\Events;

use App\Http\Resources\DrawnNumberResource;
use App\Models\DrawnNumber;
use App\Models\Game;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NumberDrawn implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Game $game,
        public readonly DrawnNumber $drawnNumber,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('game.' . $this->game->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'drawn_number' => new DrawnNumberResource($this->drawnNumber),
            'total_drawn'  => $this->game->drawnNumbers()->count(),
        ];
    }
}
