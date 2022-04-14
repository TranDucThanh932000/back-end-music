<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSingersongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SingerSongs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('singer_id');
            $table->foreign('singer_id')->references('id')->on('singers');
            $table->unsignedInteger('song_id');
            $table->foreign('song_id')->references('id')->on('Songs');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SingerSongs');
    }
}
