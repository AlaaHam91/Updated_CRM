<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAcquaintancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    public function up()
    {
        Schema::create('acquaintances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('job_id');
            $table->string('job_place')->nullable();
            $table->string('bank')->nullable();
            $table->string('bank_account')->nullable();
            $table->unsignedBigInteger('delegate_id')->nullable();
            $table->integer('commission_percentage')->nullable();
            $table->boolean('is_deserving_commission')->default(false);
            $table->timestamps();

            $table->foreign('person_id')
                ->on('persons')
                ->references('id')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreign('created_by')
                ->on('users')
                ->references('id');
            $table->foreign('job_id')
                ->on('jobs')
                ->references('id');
            $table->foreign('delegate_id')
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
        Schema::dropIfExists('acquaintances');
    }
}
