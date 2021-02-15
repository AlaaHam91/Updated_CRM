<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpersonLivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cperson_lives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->unsignedBigInteger('job_id')->nullable(true);
            $table->string('job_title')->nullable(true);
            $table->morphs('personable');
            $table->timestamps();

            $table->foreign('user_id')
                  ->on('users')
                  ->references('id');

            $table->foreign('job_id')
                  ->on('jobs')
                  ->references('id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cperson_lives');
    }
}
