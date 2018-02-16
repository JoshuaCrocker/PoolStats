<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsHppTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_hpp', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->integer('player_id')->unsigned();
            $table->integer('score')->unsigned();

            $table->foreign('team_id')->references('id')->on('teams');
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
        Schema::table('stats_hpp', function (Blueprint $table) {
            $table->dropForeign('stats_hpp_team_id_foreign');
            $table->dropForeign('stats_hpp_player_id_foreign');
        });

        Schema::dropIfExists('stats_hpp');
    }
}
