<?php

namespace App\Models\Tenant;

class PosStation extends ModelTenant
{
    protected $with = ['establishment', 'warehouse'];
    
    protected $fillable = [
        'name',
        'description',
        'establishment_id',
        'warehouse_id',
        'user_id',
        'active'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class)->withDefault();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class)->withDefault();
    }
}