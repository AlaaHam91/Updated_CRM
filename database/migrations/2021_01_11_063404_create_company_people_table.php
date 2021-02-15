<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_people', function (Blueprint $table) {
            $table->id();
            $table->unique('person_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('person_id');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_responsible')->default(false);
            $table->unsignedBigInteger('contact_source_id')->nullable();;
            $table->unsignedBigInteger('acquaintance_method_id')->nullable();;
            $table->unsignedBigInteger('acquaintance_id')->nullable();;
            $table->unsignedBigInteger('language_id')->nullable();;
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_removed')->default(false);
            $table->timestamps();

            $table->foreign('company_id')
                ->on('companies')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('person_id')
                ->on('persons')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('contact_source_id')
                ->on('contact_sources')
                ->references('id');

            $table->foreign('acquaintance_method_id')
                ->on('acquaintance_methods')
                ->references('id');

            $table->foreign('acquaintance_id')
                ->on('acquaintances')
                ->references('id');

            $table->foreign('language_id')
                ->on('languages')
                ->references('id');

            $table->foreign('user_id')
                ->on('users')
                ->references('id');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_people');
    }
}
