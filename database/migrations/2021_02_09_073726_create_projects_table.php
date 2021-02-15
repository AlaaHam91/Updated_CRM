<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    /*
     name
latin_name
code
company_id
employee_id
branch_id
department_id
country_id
GPS_Location_Y
GPS_Location_X
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('latin_name');
            $table->string('code');
            $table->foreignId('company_id');
            $table->foreign('company_id')->on('companies')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('employee_id');
            $table->foreign('employee_id')->on('users')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('branch_id');
            $table->foreign('branch_id')->on('branches')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('department_id');
            $table->foreign('department_id')->on('departments')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('country_id');
            $table->foreign('country_id')->on('countries')
                ->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('longitude');
            $table->string('latitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
