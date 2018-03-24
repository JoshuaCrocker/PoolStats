<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatVenuePerformancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_venue_performances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('player_id')->unsigned();
            $table->integer('venue_id')->unsigned();
            $table->integer('won')->unsigned();
            $table->integer('played')->unsigned();
            $table->timestamps();
        });

        Schema::table('stat_venue_performances', function (Blueprint $table) {
            $table->foreign('player_id')->references('id')->on('players');
            $table->foreign('venue_id')->references('id')->on('venues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stat_venue_performances', function (Blueprint $table) {
            $table->dropForeign('stat_venue_performances_player_id_foreign');
            $table->dropForeign('stat_venue_performances_venue_id_foreign');
        });

        Schema::dropIfExists('stat_venue_performances');
    }
}
