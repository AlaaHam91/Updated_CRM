<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_positions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_method_id');
            $table->string('longitude');
            $table->string('latitude');
            $table->morphs('cipositionable');
            $table->foreign('communication_method_id')
               ->on('communication_methods')
               ->references('id')
               ->cascadeOnDelete()
               ->cascadeOnUpdate();

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
        Schema::dropIfExists('ci_positions');
    }
}
