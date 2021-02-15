<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /*
     * 'name','latin_name','logo','type','parent_id','contact_source_id',
        'is_visitor','user_id','acquaintance_method_id','prefered_language_id',
        'acquaintance_id','number_of_employees','is_active'
     */
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('latin_name',100);
            $table->string('logo',100)->nullable();
            $table->enum('type',['main','branch']);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('contact_source_id')->nullable();;
            $table->boolean('is_visitor')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();;
            $table->unsignedBigInteger('acquaintance_method_id')->nullable();;
            $table->unsignedBigInteger('preferred_language_id')->nullable();;
            $table->unsignedBigInteger('acquaintance_id')->nullable();;
            $table->string('number_of_employees')->nullable();;
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->foreign('parent_id')->on('companies')->references('id')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('contact_source_id')
                ->on('contact_sources')
                ->references('id');
            $table->foreign('user_id')
                ->on('users')
                ->references('id');
            $table->foreign('acquaintance_method_id')
                ->on('acquaintance_methods')
                ->references('id');
            $table->foreign('preferred_language_id')
                ->on('languages')
                ->references('id');
            $table->foreign('acquaintance_id')
                ->on('acquaintances')
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
        Schema::dropIfExists('companies');
    }
}
