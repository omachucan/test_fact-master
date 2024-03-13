<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddStateTypeIdToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->char('state_type_id', 2)->default('01')->after('warehouse_id');

            $table->foreign('state_type_id')->references('id')->on('state_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->dropColumn('state_type_id');
        });
    }
}
