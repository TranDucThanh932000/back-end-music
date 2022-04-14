<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComposersongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ComposerSongs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('composer_id');
            $table->foreign('composer_id')->references('id')->on('composers');
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
        Schema::dropIfExists('ComposerSongs');
    }
}
