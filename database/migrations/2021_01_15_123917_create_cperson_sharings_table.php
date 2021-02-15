<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpersonSharingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cperson_sharings', function (Blueprint $table) {
            $table->primary(['company_person_id','user_id']);
            $table->unsignedBigInteger('company_person_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('company_person_id')
                  ->on('company_people')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreign('user_id')
                  ->on('users')
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
        Schema::dropIfExists('cperson_sharings');
    }
}
