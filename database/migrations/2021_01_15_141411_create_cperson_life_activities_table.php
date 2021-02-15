<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpersonLifeActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cperson_life_activities', function (Blueprint $table) {

            $table->primary(['cperson_life_id','activity_id']);
            $table->unsignedBigInteger('cperson_life_id');
            $table->unsignedBigInteger('activity_id');
            $table->foreign('cperson_life_id')
                  ->on('cperson_lives')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreign('activity_id')
                  ->on('activities')
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
        Schema::dropIfExists('cperson_life_activities');
    }
}
