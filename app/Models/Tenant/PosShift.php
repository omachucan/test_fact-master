<?php

namespace App\Models\Tenant;

class PosShift extends ModelTenant
{
    protected $with = ['user'];

    protected $fillable = [
        'id',
        'pos_station_id',
        'user_id',
        'start_date',
        'closed_date',
        'open_amount',
        'close_amount',
        'sales_count',
        'status',
        'created_at',
        'updated_at'
    ];

    // protected $dates = [
    //     'created_at',
    //     'updated_at'
    // ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}