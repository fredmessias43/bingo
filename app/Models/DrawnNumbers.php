<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class DrawnNumbers extends Model
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
		'game_id',
		'number',
	];
	
	/**
	 * The "booted" method of the model.
	 */
	protected static function booted(): void
	{
		static::creating(function ($drawnNumbers) {
			$drawnNumbers->id = Str::uuid();
		});
	}

	public function game(): BelongsTo
	{
		return $this->belongsTo(Game::class);
	}
	

};
