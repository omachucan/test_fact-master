<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantUpdateTotalPaidToPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        DB::statement("UPDATE purchases SET  total_paid = total");    
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
    }
}
