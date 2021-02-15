<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequiredActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::create('required_actions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('latin_name');
            $table->time('default_time');
            $table->string('close_type');
            $table->dateTime('required_delivery_date');
            $table->integer('progress_rate');
            $table->integer('implementation_no');
            $table->foreignId('finish_type_id');
            $table->foreignId('confirm_project');
            $table->integer('variable_field');
            $table->integer('correct_value');
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
        Schema::dropIfExists('required_actions');
    }
}
