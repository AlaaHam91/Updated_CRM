<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_mobiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_method_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->string('number',20);
            $table->morphs('cimobilable');
            $table->foreign('communication_method_id')
                   ->on('communication_methods')
                   ->references('id');
            $table->foreign('country_id')
                ->on('countries')
                ->references('id');
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
        Schema::dropIfExists('ci_mobiles');
    }
}
