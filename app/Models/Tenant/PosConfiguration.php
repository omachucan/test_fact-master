<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ModelTenant;

class PosConfiguration extends ModelTenant
{
    protected $fillable = [ 
        'cash_shifts',
    ];  
}