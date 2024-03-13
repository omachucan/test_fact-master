<?php

namespace App\CoreDevPro\Requests\Web\Validation;

class SaleNoteValidation
{
    public static function validation($inputs) {
        
        if(isset($inputs['sale_note_id'])){
            $inputs['series'] = $inputs['series_id'];            
           
        }else{
            $series = Functions::findSeries($inputs);
            $inputs['series'] = $series->number;
        }

        unset($inputs['series_id']);
        
        Functions::DNI($inputs);
        
        return $inputs;
    }
}