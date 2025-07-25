<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Card extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $keyType = "string";
	public $incrementing = false;

	/**
	 * The attributes that should be cast.
	 *
	 * @var array
	 */
	protected $casts = [
		'numbers' => 'array',
	];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'game_id',
		'player_id',
		'numbers',
	];

	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		static::creating(function ($cards) {
			$cards->id = Str::uuid7();
		});
	}

	public function game(): BelongsTo
	{
		return $this->belongsTo(Game::class);
	}

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}


};
