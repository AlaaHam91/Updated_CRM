<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('latin_name');
            $table->string('user_name')->nullable();
            $table->string('account_number',20)->nullable();
            $table->string('identity_number',25)->nullable();
            $table->string('job_title')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('tickets_email')->nullable();
            $table->string('notes')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('administration_id')->nullable();
            $table->foreign('administration_id')->on('administrations')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable();
            $table->foreign('branch_id')->on('branches')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('department_id')->nullable();
            $table->foreign('department_id')->on('departments')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('manager_id')->nullable();
            $table->foreign('manager_id')->on('users')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('job_id')->nullable();
            $table->foreign('job_id')->on('jobs')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('hierarchy_id')->nullable();
            $table->foreign('hierarchy_id')->on('hierarchies')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('managerial_level_id')->nullable();
            $table->foreign('managerial_level_id')->on('managerial_levels')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
