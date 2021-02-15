<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrencyUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('currency_id');
            $table->string('name');
            $table->string('latin_name');
            $table->decimal('unit_value');
            $table->timestamps();
            $table->foreign('currency_id')
                ->on('currencies')
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
        Schema::dropIfExists('currency_units');
    }
}
