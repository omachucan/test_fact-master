<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class InsertPermissionCashControl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('modules')->where('value', 'pos')->update(['description' => 'Control de caja']);

        DB::table('modules')->insert([
            ['value' => 'pos-stations', 'description' => 'Terminal POS', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
        ]);

        DB::table('permissions')->insert([
            ['name' => 'Agregar Terminal POS', 'slug' => 'tenant.pos-stations.store', 'description' => 'Agregar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Listar/Ver Terminal POS', 'slug' => 'tenant.pos-stations.index', 'description' => 'Listar/Ver', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Editar Terminal POS', 'slug' => 'tenant.pos-stations.update', 'description' => 'Editar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Eliminar Terminal POS', 'slug' => 'tenant.pos-stations.destroy', 'description' => 'Eliminar', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')],
            ['name' => 'Configuración Turnos', 'slug' => 'tenant.configuration.shifts', 'description' => 'Turnos', 'created_at' => DB::raw('NOW()'), 'updated_at' => DB::raw('NOW()')]
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
