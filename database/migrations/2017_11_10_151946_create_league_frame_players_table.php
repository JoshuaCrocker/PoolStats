<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueFramePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_frame_players', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('league_frame_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->boolean('winner')->default(FALSE);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('league_frame_id')->references('id')->on('league_frames');
            $table->foreign('player_id')->references('id')->on('players');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('league_frame_players', function(Blueprint $table) {
            $table->dropForeign('league_frame_players_league_frame_id_foreign');
            $table->dropForeign('league_frame_players_player_id_foreign');
        });

        Schema::dropIfExists('league_frame_players');
    }
}
