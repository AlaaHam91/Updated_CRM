<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectMenuOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('select_menu_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('select_menu_id');
            $table->string('option');
            $table->string('latin_option');
            $table->boolean('is_selected');
            $table->foreign('select_menu_id')
                  ->references('id')
                  ->on('select_menus')
                  ->cascadeOnDelete()
                  ->cascadeOnUpdate();
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
        Schema::dropIfExists('select_menu_options');
    }
}
