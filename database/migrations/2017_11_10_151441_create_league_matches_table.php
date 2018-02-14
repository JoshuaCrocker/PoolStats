<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeagueMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('league_id')->unsigned();
            $table->integer('venue_id')->unsigned();
            $table->date('match_date');
            $table->integer('home_team_id')->unsigned();
            $table->integer('away_team_id')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('league_id')->references('id')->on('leagues');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->foreign('home_team_id')->references('id')->on('teams');
            $table->foreign('away_team_id')->references('id')->on('teams');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('league_matches', function (Blueprint $table) {
            $table->dropForeign('league_matches_league_id_foreign');
            $table->dropForeign('league_matches_venue_id_foreign');
            $table->dropForeign('league_matches_home_team_id_foreign');
            $table->dropForeign('league_matches_away_team_id_foreign');
        });

        Schema::dropIfExists('league_matches');
    }
}
