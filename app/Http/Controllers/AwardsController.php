<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Awards;
use App\Http\Requests\AwardsRequest;
use App\Http\Resources\AwardsCollection;
use App\Http\Resources\AwardsResource;


class AwardsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awards = Awards::paginate(request()->query('per_page', 10));

        return inertia('Awards', [
            'awards' => AwardsResource::collection($awards),
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
    public function store(AwardsRequest $request)
    {
        try {
            $awards = Awards::create($request->validated());

            return to_route('awards.index')->with([
                'message' => 'Awards created successfully',
                'awards' => $awards,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Awards $awards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Awards $awards)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AwardsRequest $request, Awards $awards)
    {
        try {
            $awards->update($request->validated());

            return to_route('awards.index')->with([
                'message' => 'Awards updated successfully',
                'awards' => $awards,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update awards']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Awards $awards)
    {
        try {
            $awards->delete();

            return to_route('awards.index')->with([
                'message' => 'Awards deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete awards']);
        }
    }
};
