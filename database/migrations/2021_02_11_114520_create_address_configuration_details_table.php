<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressConfigurationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_configuration_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adr_config_id');
            $table->foreign('adr_config_id')
                ->on('address_configurations')
                ->references('id')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('label');
            $table->string('latin_label');
            $table->boolean('is_required');
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
        Schema::dropIfExists('address_configuration_details');
    }
}
