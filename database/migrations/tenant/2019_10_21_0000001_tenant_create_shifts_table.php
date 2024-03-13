<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_shifts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->integer('pos_station_id')->unsigned();
            $table->dateTime('start_date');
            $table->dateTime('closed_date')->nullable();
            $table->decimal('open_amount', 12, 2);
            $table->decimal('close_amount', 12, 2)->default(0)->nullable();
            $table->integer('sales_count')->default(0);
            $table->enum('status', ['open', 'suspended', 'blocked', 'close'])->default('open');

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('pos_station_id')->references('id')->on('pos_stations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_shifts');
    }
}
