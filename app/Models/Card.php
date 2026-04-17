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

	/**
	 * Check if this card has a winning pattern given the drawn numbers.
	 *
	 * Card numbers are stored as a flat 25-element array in row-major order:
	 *   [0..4]  = row 0,  [5..9]  = row 1, ..., [20..24] = row 4
	 *
	 * Win types: line (any row), column (any column), diagonal (main or anti),
	 *            corners (4 corners), full (all 25 numbers).
	 */
	public function hasWon(array $drawnNumbers, string $winType = 'line'): bool
	{
		$numbers = $this->numbers;
		$isMarked = fn(int $i) => in_array($numbers[$i], $drawnNumbers);

		return match ($winType) {
			'line'     => $this->anyRow($isMarked),
			'column'   => $this->anyColumn($isMarked),
			'diagonal' => $this->anyDiagonal($isMarked),
			'corners'  => $isMarked(0) && $isMarked(4) && $isMarked(20) && $isMarked(24),
			'full'     => count(array_filter($numbers, fn($n) => in_array($n, $drawnNumbers))) === 25,
			default    => false,
		};
	}

	private function anyRow(callable $isMarked): bool
	{
		for ($row = 0; $row < 5; $row++) {
			$base = $row * 5;
			if ($isMarked($base) && $isMarked($base + 1) && $isMarked($base + 2) && $isMarked($base + 3) && $isMarked($base + 4)) {
				return true;
			}
		}
		return false;
	}

	private function anyColumn(callable $isMarked): bool
	{
		for ($col = 0; $col < 5; $col++) {
			if ($isMarked($col) && $isMarked($col + 5) && $isMarked($col + 10) && $isMarked($col + 15) && $isMarked($col + 20)) {
				return true;
			}
		}
		return false;
	}

	private function anyDiagonal(callable $isMarked): bool
	{
		$main = $isMarked(0) && $isMarked(6) && $isMarked(12) && $isMarked(18) && $isMarked(24);
		$anti = $isMarked(4) && $isMarked(8) && $isMarked(12) && $isMarked(16) && $isMarked(20);
		return $main || $anti;
	}


};
