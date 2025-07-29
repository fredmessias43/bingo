<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('player_invites', function (Blueprint $table) {
			$table->uuid('id')->primary();
			$table->uuid('player_id');
			$table->uuid('game_id');
			$table->enum('status', ['pending','accepted','declined'])->default('pending');
	
			$table->foreign('player_id')->references('id')->on('players');
			$table->foreign('game_id')->references('id')->on('games');
	
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('player_invites');
	}

};
