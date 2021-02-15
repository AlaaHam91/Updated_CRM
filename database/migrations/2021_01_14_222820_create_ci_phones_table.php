<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiPhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_phones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_method_id');
            $table->unsignedBigInteger('country_id')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('number',20);
            $table->string('transfer_number',10);
            $table->morphs('ciphonable');
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
        Schema::dropIfExists('ci_phones');
    }
}
