<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreatePosDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_documents', function (Blueprint $table) {
            $table->increments('id');

            $table->string('table_name', 20)->default('pos_stations');
            $table->integer('table_id')->unsigned();
            $table->string('table_name2', 20)->default('documents');
            $table->integer('table_id2')->unsigned();           

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
        Schema::dropIfExists('pos_documents');
    }
}
