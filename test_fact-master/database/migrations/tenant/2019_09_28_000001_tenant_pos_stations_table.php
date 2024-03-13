<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPosStationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_stations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->unsignedBigInteger('user_id');
            $table->text('description')->nullable();
            $table->unsignedInteger('establishment_id'); 
            $table->unsignedInteger('warehouse_id'); 
            $table->boolean('active')->default(true);

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('establishment_id')->references('id')->on('establishments');

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
        Schema::dropIfExists('pos_stations');
    }
}