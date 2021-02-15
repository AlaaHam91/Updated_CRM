<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiSocialMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_social_mobiles', function (Blueprint $table) {
            $table->primary(['ci_mobile_id','social_network_id']);
            $table->unsignedBigInteger('ci_mobile_id');
            $table->unsignedBigInteger('social_network_id');

            $table->foreign('ci_mobile_id')
                  ->on('ci_mobiles')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreign('social_network_id')
                  ->on('social_networks')
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
        Schema::dropIfExists('ci_social_mobiles');
    }
}
