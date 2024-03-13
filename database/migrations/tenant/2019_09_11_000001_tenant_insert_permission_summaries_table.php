<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class TenantInsertPermissionSummariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('permissions')->insert([
            ['name' => 'Eliminar  resúmenes ', 'slug' => 'tenant.summaries.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
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
