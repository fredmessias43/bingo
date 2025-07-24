<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Player extends Model
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
		'name',
		'email',
		'game_id',
	];
	
	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		static::creating(function ($player) {
			$player->id = Str::uuid();
		});
	}

	public function playerInvites(): HasMany
	{
		return $this->hasMany(PlayerInvite::class);
	}
	
	public function cards(): HasMany
	{
		return $this->hasMany(Cards::class);
	}
	
	public function game(): BelongsTo
	{
		return $this->belongsTo(Game::class);
	}
	

};
