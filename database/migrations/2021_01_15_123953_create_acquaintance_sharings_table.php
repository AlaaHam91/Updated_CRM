<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcquaintanceSharingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acquaintance_sharings', function (Blueprint $table) {
            $table->primary(['acquaintance_id','user_id']);
            $table->unsignedBigInteger('acquaintance_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('acquaintance_id')
                  ->on('acquaintances')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreign('user_id')
                  ->on('users')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acquaintance_sharings');
    }
}
