<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DrawnNumbers;
use App\Http\Requests\DrawnNumbersRequest;
use App\Http\Resources\DrawnNumbersCollection;
use App\Http\Resources\DrawnNumbersResource;


class DrawnNumbersController extends Controller
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
			$drawnNumbers = DrawnNumbers::all();

			return response()->json([
				'status' => 200,
				'data' => DrawnNumbersResource::collection($drawnNumbers),
			], 200);
		}
		catch(\Throwable $th)
		{
			throw new \Exception('DrawnNumbers can not be listed.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \App\Http\Requests\DrawnNumbersRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store(DrawnNumbersRequest $request)
	{
		try
		{
			$drawnNumbers = DrawnNumbers::create($request->validated());

			return response()->json([
				'status' => 201,
				'message' => trans('DrawnNumbers created successfully.'),
				'data' => new DrawnNumbersResource($drawnNumbers),
			], 201);
		}
		catch (\Throwable $th)
		{
			throw new \Exception('DrawnNumbers can not be created.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  \App\Models\DrawnNumbers $drawnNumbers
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(DrawnNumbers $drawnNumbers)
	{
		try
		{
			return response()->json([
				'status' => 200,
				'data' => new DrawnNumbersResource($drawnNumbers),
			], 200);
		}
		catch(\Throwable $th)
		{
			throw new \Exception('DrawnNumbers can not be shown.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \App\Http\Requests\DrawnNumbersRequest $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update(DrawnNumbersRequest $request, DrawnNumbers $drawnNumbers)
	{
		try
		{
			$drawnNumbers->update($request->validated());

			return response()->json([
				'status' => 201,
				'message' => trans('DrawnNumbers updated successfully.'),
				'data' => new DrawnNumbersResource($drawnNumbers),
			], 201);
		}
		catch (\Throwable $th)
		{
			throw new \Exception('DrawnNumbers can not be updated.' . $th->getMessage(), 500);
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\DrawnNumbers $drawnNumbers
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(DrawnNumbers $drawnNumbers)
	{
		try
		{
			$drawnNumbers->delete();

			return response()->json([
				'status' => 204,
				'message' => trans('DrawnNumbers deleted successfully.'),
				'data' => new DrawnNumbersResource($drawnNumbers),
			], 204);
		}
		catch(\Throwable $th)
		{
			throw new \Exception('DrawnNumbers can not be deleted.' . $th->getMessage(), 500);
		}
	}

};
