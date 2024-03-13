<?php

namespace App\Models\Tenant;

class PosDocument extends ModelTenant
{
    protected $fillable = [
        'table_name',
        'table_id',
        'table_name2',
        'table_id2'
    ];
}
