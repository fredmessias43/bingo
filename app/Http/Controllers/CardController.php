<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardCollection;
use App\Http\Resources\CardResource;


class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Card::paginate(request()->query('per_page', 10));

        return inertia('admin/Card', [
            'cards' => CardResource::collection($cards),
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
    public function store(CardRequest $request)
    {
        try {
            $cards = Card::create($request->validated());

            return to_route('cards.index')->with([
                'message' => 'Card created successfully',
                'cards' => $cards,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $cards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $cards)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardRequest $request, Card $cards)
    {
        try {
            $cards->update($request->validated());

            return to_route('cards.index')->with([
                'message' => 'Card updated successfully',
                'cards' => $cards,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update cards']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $cards)
    {
        try {
            $cards->delete();

            return to_route('cards.index')->with([
                'message' => 'Card deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete cards']);
        }
    }
};
