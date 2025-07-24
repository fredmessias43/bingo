<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class PlayerInvite extends Model
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
	];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'id',
		'player_id',
		'game_id',
		'status',
	];
	
	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		static::creating(function ($playerInvite) {
			$playerInvite->id = Str::uuid();
		});
	}

	public function player(): BelongsTo
	{
		return $this->belongsTo(Player::class);
	}
	
	public function game(): BelongsTo
	{
		return $this->belongsTo(Game::class);
	}
	

};
