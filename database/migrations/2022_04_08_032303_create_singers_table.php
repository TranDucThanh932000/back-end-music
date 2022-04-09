<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSingersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('singers', function (Blueprint $table) {
            $table->increments('id');
            //dùng cái foreignId này nó sẽ tự define ra là thằng user_id này sẽ chỉ đến thằng id của bảng user
            //làm khóa ngoại cho nó
            //thay cho cách củ chuối này
            //$table->unsignedBigInteger('user_id');
            //$table->foreign('user_id')->references('id')->on('users');
            //dùng cái ->onDelete('cascade') này thì xóa thằng mẹ thì thằng con cũng xóa luôn, nhưng mà 
            //như vậy có vẻ không hay bằng softDelete nhỉ
            $table->foreignId('user_id')->constrained();
            $table->string('nickname')->nullable(false);
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
        Schema::dropIfExists('singers');
    }
}
