<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentAdminDivsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_admin_divs', function (Blueprint $table) {
            $table->primary(['admin_division_id','agent_id']);
            $table->unsignedBigInteger('admin_division_id');
            $table->unsignedBigInteger('agent_id');

            $table->foreign('admin_division_id')
                  ->on('admin_divisions')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
             $table->foreign('agent_id')
                  ->on('agents')
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
        Schema::dropIfExists('agent_admin_divs');
    }
}
