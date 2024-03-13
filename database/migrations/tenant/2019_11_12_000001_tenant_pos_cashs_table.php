<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPosCashsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_cashs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pos_shift_id');
            $table->char('type', 1);
            $table->decimal('value', 12, 2);
            $table->text('observations')->nullable();
            $table->unsignedInteger('account_id');

            $table->foreign('pos_shift_id')->references('id')->on('pos_shifts');
            $table->foreign('account_id')->references('id')->on('accounts');

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
        Schema::dropIfExists('pos_cashs');
    }
}