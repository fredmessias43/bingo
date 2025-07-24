<?php

namespace App\Http\Controllers\Api;

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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(GameRequest $request)
    {
        try {
            $game = Game::create($request->validated());

            return to_route('games.index')->with([
                'message' => 'Game created successfully',
                'game' => $game,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Game $game)
    {
        //
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
