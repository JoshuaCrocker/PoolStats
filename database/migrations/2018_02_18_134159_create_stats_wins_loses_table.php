<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatsWinsLosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stats_wins_loses', function (Blueprint $table) {
            $table->integer('player_id')->unsigned();
            $table->integer('wins')->unsigned();
            $table->integer('loses')->unsigned();
            $table->integer('draws')->unsigned();

            $table->primary('player_id');
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
        Schema::table('stats_wins_loses', function (Blueprint $table) {
            $table->dropForeign('stats_wins_loses_player_id_foreign');
        });

        Schema::dropIfExists('stats_wins_loses');
    }
}
