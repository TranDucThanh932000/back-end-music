<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSongsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Songs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('lyrics');
            $table->string('timeDuration');
            $table->string('image');
            $table->string('src');
            $table->date('releaseDate');
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
        Schema::dropIfExists('Songs');
    }
}
