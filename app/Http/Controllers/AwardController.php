<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Http\Requests\AwardRequest;
use App\Http\Resources\AwardCollection;
use App\Http\Resources\AwardResource;


class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $awards = Award::paginate(request()->query('per_page', 10));

        return inertia('Award', [
            'awards' => AwardResource::collection($awards),
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
    public function store(AwardRequest $request)
    {
        try {
            $awards = Award::create($request->validated());

            return to_route('awards.index')->with([
                'message' => 'Award created successfully',
                'awards' => $awards,
            ]);
        } catch (\Throwable $th) {
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Award $awards)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Award $awards)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AwardRequest $request, Award $awards)
    {
        try {
            $awards->update($request->validated());

            return to_route('awards.index')->with([
                'message' => 'Award updated successfully',
                'awards' => $awards,
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to update awards']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Award $awards)
    {
        try {
            $awards->delete();

            return to_route('awards.index')->with([
                'message' => 'Award deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return back()->withErrors(['error' => 'Failed to delete awards']);
        }
    }
};
