<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_products', function (Blueprint $table) {
            $table->primary(['agent_id','product_id']);
            $table->unsignedBigInteger('agent_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('product_id')
                  ->on('products')
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
        Schema::dropIfExists('agent_products');
    }
}
