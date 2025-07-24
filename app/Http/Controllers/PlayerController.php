<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Http\Requests\PlayerRequest;
use App\Http\Resources\PlayerCollection;
use App\Http\Resources\PlayerResource;


class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = Player::paginate(request()->query('per_page', 10));

        return inertia('Players', [
            'players' => PlayerResource::collection($players),
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
    public function store(PlayerRequest $request)
    {
        try {
            $player = Player::create($request->validated());

            return to_route('players.index')->with([
                'message' => 'Player created successfully',
                'player' => $player,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerRequest $request, Player $player)
    {
        try {
            $player->update($request->validated());

            return to_route('players.index')->with([
                'message' => 'Player updated successfully',
                'player' => $player,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update player']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        try {
            $player->delete();

            return to_route('players.index')->with([
                'message' => 'Player deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete player']);
        }
    }
};
