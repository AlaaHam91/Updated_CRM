<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiSocialEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_social_emails', function (Blueprint $table) {
            $table->primary(['ci_email_id','social_network_id']);
            $table->unsignedBigInteger('ci_email_id');
            $table->unsignedBigInteger('social_network_id');
            $table->foreign('ci_email_id')
                  ->on('ci_emails')
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
        Schema::dropIfExists('ci_social_emails');
    }
}
