<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_method_id');
            $table->text('note');
            $table->text('declaration');
            $table->morphs('cinotable');
            $table->timestamps();
            $table->foreign('communication_method_id')
                ->on('communication_methods')
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
        Schema::dropIfExists('ci_notes');
    }
}
