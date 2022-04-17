<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPlaylistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('user_playlists', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->bigInteger('user_id')->unsigned()->index();
        //     $table->foreign('user_id')->references('id')->on('users');
        //     $table->unsignedInteger('playlist_id');
        //     $table->foreign('playlist_id')->references('id')->on('playlists');
        //     $table->timestamps();
        //     $table->softDeletes();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_playlists');
    }
}
