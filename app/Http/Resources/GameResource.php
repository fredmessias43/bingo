<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;


class GameResource extends JsonResource
{

	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request)
	{
	    return [
			'id' => $this->id,
			'name' => $this->name,
			'description' => $this->description,
			'max_players' => $this->max_players,
			'players_count' => $this->players_count ?? 0,
			'status' => $this->status,
			'game_data' => $this->game_data,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at,
		];
	}

};
