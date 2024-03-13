<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeDecimalQuantityTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->decimal('quantity', 12, 4)->change();
        });

        Schema::table('quotation_items', function (Blueprint $table) {
            $table->decimal('quantity', 12, 4)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
    }
}
