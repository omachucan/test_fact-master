<?php

namespace App\Models\Tenant;

class Configuration extends ModelTenant
{
    protected $fillable = ['send_auto', 'cron', 'decimal','igv_sale_note','exchange_rate'];
}