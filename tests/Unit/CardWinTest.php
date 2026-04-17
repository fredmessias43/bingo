<?php

use App\Models\Card;

// Card numbers stored as a flat 25-element array (row-major, 5x5):
//  [0]  [1]  [2]  [3]  [4]   ← row 0:  numbers  1– 5
//  [5]  [6]  [7]  [8]  [9]   ← row 1:  numbers  6–10
// [10] [11] [12] [13] [14]   ← row 2:  numbers 11–15
// [15] [16] [17] [18] [19]   ← row 3:  numbers 16–20
// [20] [21] [22] [23] [24]   ← row 4:  numbers 21–25

$cardNumbers = [
     1,  2,  3,  4,  5,
     6,  7,  8,  9, 10,
    11, 12, 13, 14, 15,
    16, 17, 18, 19, 20,
    21, 22, 23, 24, 25,
];

beforeEach(function () use ($cardNumbers) {
    // Instantiate without persisting — hasWon() only reads $this->numbers
    $this->card = new Card(['numbers' => $cardNumbers]);
});

// ── No drawn numbers ────────────────────────────────────────────────────────

test('no win when no numbers are drawn', function () {
    expect($this->card->hasWon([], 'line'))->toBeFalse()
        ->and($this->card->hasWon([], 'column'))->toBeFalse()
        ->and($this->card->hasWon([], 'diagonal'))->toBeFalse()
        ->and($this->card->hasWon([], 'corners'))->toBeFalse()
        ->and($this->card->hasWon([], 'full'))->toBeFalse();
});

// ── Line (horizontal row) ───────────────────────────────────────────────────

test('wins with any complete horizontal row', function () {
    expect($this->card->hasWon([1, 2, 3, 4, 5], 'line'))->toBeTrue()       // row 0
        ->and($this->card->hasWon([6, 7, 8, 9, 10], 'line'))->toBeTrue()   // row 1
        ->and($this->card->hasWon([11, 12, 13, 14, 15], 'line'))->toBeTrue() // row 2
        ->and($this->card->hasWon([16, 17, 18, 19, 20], 'line'))->toBeTrue() // row 3
        ->and($this->card->hasWon([21, 22, 23, 24, 25], 'line'))->toBeTrue(); // row 4
});

test('does not win line with only 4 of 5 in a row', function () {
    expect($this->card->hasWon([1, 2, 3, 4], 'line'))->toBeFalse();
});

test('does not win line with numbers from different rows', function () {
    // 1–4 from row 0, 6 from row 1
    expect($this->card->hasWon([1, 2, 3, 4, 6], 'line'))->toBeFalse();
});

test('extra drawn numbers outside the card do not affect line win', function () {
    expect($this->card->hasWon([1, 2, 3, 4, 5, 30, 50, 70], 'line'))->toBeTrue();
});

// ── Column (vertical) ───────────────────────────────────────────────────────

test('wins with any complete column', function () {
    // col 0: indices 0,5,10,15,20 → numbers 1,6,11,16,21
    expect($this->card->hasWon([1, 6, 11, 16, 21], 'column'))->toBeTrue()
        // col 4: indices 4,9,14,19,24 → numbers 5,10,15,20,25
        ->and($this->card->hasWon([5, 10, 15, 20, 25], 'column'))->toBeTrue();
});

test('does not win column with only 4 of 5 in a column', function () {
    expect($this->card->hasWon([1, 6, 11, 16], 'column'))->toBeFalse();
});

// ── Diagonal ────────────────────────────────────────────────────────────────

test('wins with main diagonal (top-left to bottom-right)', function () {
    // indices 0,6,12,18,24 → numbers 1,7,13,19,25
    expect($this->card->hasWon([1, 7, 13, 19, 25], 'diagonal'))->toBeTrue();
});

test('wins with anti-diagonal (top-right to bottom-left)', function () {
    // indices 4,8,12,16,20 → numbers 5,9,13,17,21
    expect($this->card->hasWon([5, 9, 13, 17, 21], 'diagonal'))->toBeTrue();
});

test('does not win diagonal with incomplete diagonal', function () {
    expect($this->card->hasWon([1, 7, 13, 19], 'diagonal'))->toBeFalse();
});

// ── Corners ─────────────────────────────────────────────────────────────────

test('wins with all four corners', function () {
    // indices 0,4,20,24 → numbers 1,5,21,25
    expect($this->card->hasWon([1, 5, 21, 25], 'corners'))->toBeTrue();
});

test('does not win corners with only 3 corners', function () {
    expect($this->card->hasWon([1, 5, 21], 'corners'))->toBeFalse();
});

test('extra numbers do not grant corners win', function () {
    // Many numbers drawn but not the 4 corners
    expect($this->card->hasWon([2, 3, 4, 6, 7, 8], 'corners'))->toBeFalse();
});

// ── Full card ────────────────────────────────────────────────────────────────

test('wins full card only when all 25 numbers are drawn', function () {
    $all = range(1, 25);
    expect($this->card->hasWon($all, 'full'))->toBeTrue();
});

test('does not win full card with one number missing', function () {
    $missing_last = range(1, 24);
    expect($this->card->hasWon($missing_last, 'full'))->toBeFalse();
});

// ── Unknown win type ─────────────────────────────────────────────────────────

test('unknown win type always returns false', function () {
    expect($this->card->hasWon(range(1, 25), 'unknown'))->toBeFalse();
});
