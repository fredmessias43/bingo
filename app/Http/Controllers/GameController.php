<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Http\Requests\GameRequest;
use App\Http\Resources\GameCollection;
use App\Http\Resources\GameResource;


class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $games = Game::paginate(request()->query('per_page', 10));

        return inertia('Games', [
            'games' => GameResource::collection($games),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('games/Upsert', [
            'game_modes' => [
                'data' => [
                    ["id" => "1", "code" => "traditional", "name" => "Bingo Tradicional", "description" => "Bingo tradicional com cartelas 5x5",],
                    ["id" => "2", "code" => "speed", "name" => "Bingo Rápido", "description" => "Bingo rápido com sorteio acelerado",],
                    ["id" => "3", "code" => "progressive", "name" => "Bingo Progressivo", "description" => "Prêmio aumenta a cada jogo sem vencedor",],
                    ["id" => "4", "code" => "tournament", "name" => "Torneio", "description" => "Torneio eliminatório entre jogadores",],
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameRequest $request)
    {
        try {
            $game = Game::create($request->validated());

            return back()->with([
                'message' => 'Game created successfully',
                'game' => $game,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to create game', 'exception' => $th->getMessage(),]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        return inertia('games/Show', [
            'game' => new GameResource($game),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        return inertia('games/Upsert', [
            'game' => new GameResource($game)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(GameRequest $request, Game $game)
    {
        try {
            $game->update($request->validated());

            return to_route('games.index')->with([
                'message' => 'Game updated successfully',
                'game' => $game,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update game']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Game $game)
    {
        try {
            $game->delete();

            return to_route('games.index')->with([
                'message' => 'Game deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete game']);
        }
    }
};
