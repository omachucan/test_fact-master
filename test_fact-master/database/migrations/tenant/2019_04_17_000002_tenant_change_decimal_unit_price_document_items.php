<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeDecimalUnitPriceDocumentItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('document_items', function (Blueprint $table) {
            $table->decimal('unit_price', 12, 4)->change();
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('document_items', function (Blueprint $table) {        
        });
    }
}
