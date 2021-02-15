<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('communication_method_id');
            $table->foreign('communication_method_id')->on('communication_methods')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->morphs('configurable');
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
        Schema::dropIfExists('contact_configurations');
    }
}
