<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PlayerInvite;
use App\Http\Requests\PlayerInviteRequest;
use App\Http\Resources\PlayerInviteCollection;
use App\Http\Resources\PlayerInviteResource;


class PlayerInviteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $player_invite = PlayerInvite::paginate(request()->query('per_page', 10));

        return inertia('PlayerInvites', [
            'player_invite' => PlayerInviteResource::collection($player_invite),
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
    public function store(PlayerInviteRequest $request)
    {
        try {
            $playerInvite = PlayerInvite::create($request->validated());

            return to_route('player_invite.index')->with([
                'message' => 'PlayerInvite created successfully',
                'playerInvite' => $playerInvite,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PlayerInvite $playerInvite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PlayerInvite $playerInvite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PlayerInviteRequest $request, PlayerInvite $playerInvite)
    {
        try {
            $playerInvite->update($request->validated());

            return to_route('player_invite.index')->with([
                'message' => 'PlayerInvite updated successfully',
                'playerInvite' => $playerInvite,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update playerInvite']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PlayerInvite $playerInvite)
    {
        try {
            $playerInvite->delete();

            return to_route('player_invite.index')->with([
                'message' => 'PlayerInvite deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete playerInvite']);
        }
    }
};
