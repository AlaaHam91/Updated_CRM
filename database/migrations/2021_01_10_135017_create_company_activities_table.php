<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_activities', function (Blueprint $table) {
           $table->primary(['activity_id','company_id']);
           $table->foreignId('activity_id');
           $table->foreignId('company_id');
           $table->foreign('activity_id')
               ->on('activities')->references('id')->cascadeOnDelete();

            $table->foreign('company_id')
                ->on('companies')->references('id')->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_activities');
    }
}
