<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTablePayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('table_name', 50)->default('documents')->after('id');
            $table->unsignedInteger('table_id')->after('table_name');
        });

        Schema::table('payments', function (Blueprint $table) {
            DB::statement("UPDATE payments SET table_id = document_id, table_name = 'documents' WHERE document_id IS NOT NULL");
            DB::statement("UPDATE payments SET table_id = sale_note_id, table_name = 'sale_notes' WHERE sale_note_id IS NOT NULL");

            $table->dropColumn('document_id');
            $table->dropColumn('sale_note_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn('table_name');
            $table->dropColumn('table_id');
        });
    }
}