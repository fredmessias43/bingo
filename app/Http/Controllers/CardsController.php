<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cards;
use App\Http\Requests\CardsRequest;
use App\Http\Resources\CardsCollection;
use App\Http\Resources\CardsResource;


class CardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cards = Cards::paginate(request()->query('per_page', 10));

        return inertia('admin/Cards', [
            'cards' => CardsResource::collection($cards),
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
    public function store(CardsRequest $request)
    {
        try {
            $cards = Cards::create($request->validated());

            return to_route('cards.index')->with([
                'message' => 'Cards created successfully',
                'cards' => $cards,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cards $cards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cards $cards)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CardsRequest $request, Cards $cards)
    {
        try {
            $cards->update($request->validated());

            return to_route('cards.index')->with([
                'message' => 'Cards updated successfully',
                'cards' => $cards,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update cards']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cards $cards)
    {
        try {
            $cards->delete();

            return to_route('cards.index')->with([
                'message' => 'Cards deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete cards']);
        }
    }
};
