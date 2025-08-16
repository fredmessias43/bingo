<?php

namespace App\Http\Requests;

use App\Enums\GameStatus;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Game;
use Illuminate\Validation\Rule;


class GameRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $game = $this->route('game');

        if ($game instanceof Game)
        {
            $this->merge(['id' => $game->id]);

            if (! $this->has('id'))
            {
                $this->merge(['id' => $game->id]);
            }

            if (! $this->has('name'))
            {
                $this->merge(['name' => $game->name]);
            }

            if (! $this->has('description'))
            {
                $this->merge(['description' => $game->description]);
            }

            if (! $this->has('max_players'))
            {
                $this->merge(['max_players' => $game->max_players]);
            }

            if (! $this->has('status'))
            {
                $this->merge(['status' => $game->status]);
            }

            if (! $this->has('game_data'))
            {
                $this->merge(['game_data' => $game->game_data]);
            }
        } else {
            $this->merge(['status' => GameStatus::Active->value]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'bail|required|string',
            'description' => 'bail|nullable|string',
            'max_players' => 'bail|required|string',
            'is_free' => 'bail|required|string',
            'entry_fee' => 'bail|required|string',

            //

            'awards' => 'array',
            'awards.name' => 'bail|nullable|string',
            'awards.description' => 'bail|nullable|string',
            'awards.value' => 'bail|nullable|number',
            'awards.order' => 'bail|nullable|number',
            'awards.winning_type' => 'bail|nullable|string',
            'awards.type' => 'bail|nullable|string',

            //

            'game_data' => 'array',
            'game_data.start_datetime' => 'bail|required|date',
            'game_data.automatic' => 'bail|nullable|boolean',
            'game_data.draw_speed' => 'bail|nullable|integer',
            'game_data.pause_between_prizes' => 'bail|nullable|integer',
            'game_data.game_time_duration' => 'bail|nullable|integer',
            'game_data.max_cards_per_player' => 'bail|nullable|integer',
            'game_data.min_players_to_start' => 'bail|nullable|integer',
            'game_data.allow_entry_after_start' => 'bail|nullable|boolean',
            'game_data.max_entry_time_limit' => 'bail|nullable|integer',
            'game_data.auto_verify_bingo' => 'bail|nullable|boolean',
            'game_data.enable_chat' => 'bail|nullable|boolean',
            'game_data.enable_sound' => 'bail|nullable|boolean',
            'game_data.enable_replay' => 'bail|nullable|boolean',

            //

            'status' => array(
                'bail',
                'required',
                Rule::in(GameStatus::values()),
            ),
        ];
    }
};
