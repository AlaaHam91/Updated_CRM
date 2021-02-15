<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assign_fields', function (Blueprint $table) {
            $table->primary(['additional_field_id','entity_id']);
            $table->foreignId('additional_field_id');
            $table->integer('entity_id');
            $table->foreign('additional_field_id')
                ->on('additional_fields')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
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
        Schema::dropIfExists('assign_fields');
    }
}
