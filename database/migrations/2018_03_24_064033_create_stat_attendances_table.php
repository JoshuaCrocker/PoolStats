<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stat_attendances', function (Blueprint $table) {
            $table->integer('player_id')->unsigned();
            $table->integer('played')->unsigned();
            $table->integer('total')->unsigned();
        });

        Schema::table('stat_attendances', function (Blueprint $table) {
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
        Schema::table('stat_attendances', function (Blueprint $table) {
            $table->dropForeign('stat_attendances_player_id_foreign');
        });

        Schema::dropIfExists('stat_attendances');
    }
}
