<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Game extends Model
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
		'description',
		'max_players',
		'status',
	];

	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		static::creating(function ($game) {
			$game->id = Str::uuid7();
		});
	}

    public function players(): HasMany
    {
        return $this->hasMany(Player::class);
    }

	public function awards(): HasMany
	{
		return $this->hasMany(Award::class);
	}

	public function playerInvites(): HasMany
	{
		return $this->hasMany(PlayerInvite::class);
	}

	public function cards(): HasMany
	{
		return $this->hasMany(Card::class);
	}

	public function drawnNumbers(): HasMany
	{
		return $this->hasMany(DrawnNumber::class);
	}


};
