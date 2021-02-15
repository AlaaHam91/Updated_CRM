<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('latin_name',100)->nullable();
            $table->unsignedBigInteger('job_type_id');
            $table->unsignedBigInteger('education_id');
            $table->decimal('salary')->default(0);
            $table->text('description')->nullable();
            $table->text('latin_description')->nullable();
            $table->string('level',50)->nullable();
            $table->string('latin_level',50)->nullable();
            $table->text('work_nature')->nullable();
            $table->text('latin_work_nature')->nullable();
            $table->text('skills')->nullable();
            $table->text('latin_skills')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->boolean('agents_available')->default(false);
            $table->boolean('confirm_request')->default(false);
            $table->timestamps();
            $table->foreign('job_type_id')->on('job_types')->references('id')
                  ->onUpdate('restrict')
                  ->onDelete('restrict');
            $table->foreign('education_id')->on('education')->references('id')
              ->onUpdate('restrict')
              ->onDelete('restrict');
            $table->foreign('country_id')->on('countries')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
