<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCpersonContactInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cperson_contact_infos', function (Blueprint $table) {
            $table->primary(['cperson_id', 'contact_info_id']);
            $table->unsignedBigInteger('cperson_id');
            $table->unsignedBigInteger('contact_info_id');
            $table->foreign('cperson_id')
                  ->on('company_people')
                  ->references('id')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();
            $table->foreign('contact_info_id')
                  ->on('contact_infos')
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
        Schema::dropIfExists('cperson_contact_infos');
    }
}
