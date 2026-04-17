<?php

namespace App\Http\Controllers;

use App\Events\NumberDrawn;
use App\Http\Controllers\Controller;
use App\Models\DrawnNumber;
use App\Models\Game;
use App\Http\Resources\DrawnNumberResource;
use Illuminate\Http\Request;

class DrawnNumberController extends Controller
{
    public function index(Game $game)
    {
        $drawnNumbers = $game->drawnNumbers()->orderBy('created_at')->get();

        return response()->json([
            'status' => 200,
            'data'   => DrawnNumberResource::collection($drawnNumbers),
        ]);
    }

    public function store(Request $request, Game $game)
    {
        $data = $request->validate([
            'number' => 'required|integer|between:1,75',
        ]);

        if ($game->drawnNumbers()->where('number', $data['number'])->exists()) {
            return response()->json([
                'status'  => 422,
                'message' => 'Number already drawn.',
            ], 422);
        }

        $drawnNumber = $game->drawnNumbers()->create(['number' => $data['number']]);

        NumberDrawn::dispatch($game, $drawnNumber);

        return response()->json([
            'status'  => 201,
            'message' => 'Number drawn successfully.',
            'data'    => new DrawnNumberResource($drawnNumber),
        ], 201);
    }

    public function destroy(Game $game, DrawnNumber $drawnNumber)
    {
        $drawnNumber->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Drawn number removed.',
        ]);
    }
}
