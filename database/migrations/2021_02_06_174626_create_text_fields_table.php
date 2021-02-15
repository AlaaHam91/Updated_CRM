<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTextFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('text_fields', function (Blueprint $table) {
            $table->id();
            $table->string('default_value');
            $table->integer('max_length');
            $table->enum('type',[
                'text',
                'password',
                'email',
                'textarea',
                'quill',
                'tinymce',
                'date',
                'time',
                'datetime'
            ]);
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
        Schema::dropIfExists('text_fields');
    }
}
