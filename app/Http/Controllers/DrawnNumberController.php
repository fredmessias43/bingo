<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DrawnNumber;
use App\Http\Requests\DrawnNumberRequest;
use App\Http\Resources\DrawnNumberCollection;
use App\Http\Resources\DrawnNumberResource;


class DrawnNumberController extends Controller
{

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		try
		{
			$drawnNumbers = DrawnNumber::all();

			return response()->json([
				'status' => 200,
				'data' => DrawnNumberResource::collection($drawnNumbers),
			], 200);
		}
		catch(\Throwable $th)
		{
			throw new \Exception('DrawnNumber can not be listed.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\Http\Requests\DrawnNumberRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(DrawnNumberRequest $request)
	{
		try
		{
			$drawnNumbers = DrawnNumber::create($request->validated());

			return response()->json([
				'status' => 201,
				'message' => trans('DrawnNumber created successfully.'),
				'data' => new DrawnNumberResource($drawnNumbers),
			], 201);
		}
		catch (\Throwable $th)
		{
			throw new \Exception('DrawnNumber can not be created.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\DrawnNumber $drawnNumbers
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(DrawnNumber $drawnNumbers)
	{
		try
		{
			return response()->json([
				'status' => 200,
				'data' => new DrawnNumberResource($drawnNumbers),
			], 200);
		}
		catch(\Throwable $th)
		{
			throw new \Exception('DrawnNumber can not be shown.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\DrawnNumberRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(DrawnNumberRequest $request, DrawnNumber $drawnNumbers)
	{
		try
		{
			$drawnNumbers->update($request->validated());

			return response()->json([
				'status' => 201,
				'message' => trans('DrawnNumber updated successfully.'),
				'data' => new DrawnNumberResource($drawnNumbers),
			], 201);
		}
		catch (\Throwable $th)
		{
			throw new \Exception('DrawnNumber can not be updated.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\DrawnNumber $drawnNumbers
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(DrawnNumber $drawnNumbers)
	{
		try
		{
			$drawnNumbers->delete();

			return response()->json([
				'status' => 204,
				'message' => trans('DrawnNumber deleted successfully.'),
				'data' => new DrawnNumberResource($drawnNumbers),
			], 204);
		}
		catch(\Throwable $th)
		{
			throw new \Exception('DrawnNumber can not be deleted.' . $th->getMessage(), 500);
		}
	}

};
