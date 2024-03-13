<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class TenantInsertCatPaymentMethods extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('cat_payment_methods')->insert([
            ['active' => 1, 'description' => 'Letra'],
        ]);
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {

    }
}
