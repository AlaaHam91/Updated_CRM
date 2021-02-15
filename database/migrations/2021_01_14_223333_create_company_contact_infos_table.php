<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyContactInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_contact_infos', function (Blueprint $table) {
            $table->primary(['company_id', 'contact_info_id']);
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('contact_info_id');
            $table->timestamps();
            $table->foreign('company_id')
                  ->on('companies')
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
        Schema::dropIfExists('company_contact_infos');
    }
}
