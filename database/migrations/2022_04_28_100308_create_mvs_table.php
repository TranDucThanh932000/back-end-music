<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('src');
            $table->string('image');
            $table->integer('view');
            $table->unsignedInteger('song_id');
            $table->foreign('song_id')->references('id')->on('songs');
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
        Schema::dropIfExists('mvs');
    }
}
