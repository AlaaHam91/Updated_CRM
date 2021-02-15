<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_emails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communication_method_id');

            $table->string('email');
            $table->morphs('ciemailable');
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
        Schema::dropIfExists('ci_emails');
    }
}
