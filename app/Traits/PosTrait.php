<?php

namespace App\Traits;

use App\Models\Tenant\PosShift;
use Session;

trait PosTrait
{
    public function pos_active()
    {
        $pos_station_id = Session::get('pos_station_id');
        
        $pos_shift = PosShift::where('pos_station_id', $pos_station_id)
                        ->where('user_id', auth()->id())
                        ->where('status', 'open')->first();

        $pos_shift = (is_null($pos_shift)?false:$pos_shift->id);
            
        return $pos_shift;
    }
}