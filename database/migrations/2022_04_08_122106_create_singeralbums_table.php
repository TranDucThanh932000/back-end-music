<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSingeralbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SingerAlbums', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('singer_id');
            $table->foreign('singer_id')->references('id')->on('singers');
            $table->unsignedInteger('album_id');
            $table->foreign('album_id')->references('id')->on('albums');
            // $table->foreignId('album_id')->constrained();
            // $table->foreignId('singer_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SingerAlbums');
    }
}
