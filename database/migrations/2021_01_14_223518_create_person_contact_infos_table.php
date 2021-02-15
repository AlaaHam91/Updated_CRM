<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonContactInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_contact_infos', function (Blueprint $table) {
            $table->primary(['person_id', 'contact_info_id']);
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('contact_info_id');
            $table->foreign('person_id')
                  ->on('persons')
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
        Schema::dropIfExists('person_contact_infos');
    }
}
