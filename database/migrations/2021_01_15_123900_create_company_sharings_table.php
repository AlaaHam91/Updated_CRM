<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySharingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_sharings', function (Blueprint $table) {
            $table->primary(['company_id','user_id']);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('company_id')
                  ->on('companies')
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
        Schema::dropIfExists('company_sharings');
    }
}
