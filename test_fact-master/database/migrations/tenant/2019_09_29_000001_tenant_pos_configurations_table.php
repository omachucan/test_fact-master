<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPosConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('cash_shifts')->default(true);
            $table->timestamps();
        });

        DB::table('pos_configurations')->insert([
            ['id' => 1, 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_configurations');
    }
}