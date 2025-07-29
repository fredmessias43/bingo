<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\App;


class PlayerInviteResource extends JsonResource
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
			'player_id' => $this->player_id,
			'game_id' => $this->game_id,
			'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
		];
	}

};
