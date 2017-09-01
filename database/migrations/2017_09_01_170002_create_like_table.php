<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('like', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('ware_id');
            $table->foreign('ware_id')
            ->references('id')
            ->on('ware')
            ->onUpdate('cascade')
            ->onDelete('cascade');
            $table->string('like');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('like');
    }
}
