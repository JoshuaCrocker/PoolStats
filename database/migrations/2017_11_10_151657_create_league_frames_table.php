<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueFramesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('league_frames', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('league_match_id')->unsigned();
            $table->integer('frame_number')->unsigned();
            $table->boolean('doubles')->default(FALSE);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('league_match_id')->references('id')->on('league_matches');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('league_frames', function (Blueprint $table) {
            $table->dropForeign('league_frames_league_match_id_foreign');
        });

        Schema::dropIfExists('league_frames');
    }
}
