<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable();
            $table->foreign('country_id')->on('countries')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('city_id')->nullable();
            $table->foreign('city_id')->on('cities')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->integer('fields_count')->default(1);
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
        Schema::dropIfExists('address_configurations');
    }
}
