<?php

namespace App\Models\Tenant;

class PosCash extends ModelTenant
{
    protected $table = "pos_cashs";

    protected $fillable = [
        'id',
        'pos_shift_id',
        'type',
        'value',
        'observations',
        'account_id'
    ];
}