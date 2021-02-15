<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_files', function (Blueprint $table) {
            $table->primary(['file_id','company_id']);
            $table->foreignId('company_id');
            $table->foreignId('file_id');
            $table->foreign('file_id')
                ->on('files')->references('id')->cascadeOnDelete();

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
        Schema::dropIfExists('company_files');
    }
}
