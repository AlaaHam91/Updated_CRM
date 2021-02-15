<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyAdditionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_additionals', function (Blueprint $table) {
            $table->primary(['company_id','additional_field_id']);
            $table->foreignId('company_id');
            $table->foreignId('additional_field_id');
            $table->foreign('company_id')->on('companies')->references('id')
            ->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('additional_field_id')->on('additional_fields')->references('id')
            ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_additionals');
    }
}
