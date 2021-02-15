<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiAddressDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_address_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ci_address_id');
            $table->string('value');
            $table->string('latin_value');
            $table->timestamps();
            $table->foreign('ci_address_id')
    ->on('ci_addresses')
    ->references('id')
    ->cascadeOnDelete()
    ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ci_address_detals');
    }
}
