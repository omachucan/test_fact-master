<?php

namespace App\CoreDevPro\Requests\Api\Transform\Common;

class EstablishmentTransform
{
    public static function transform($inputs)
    {
        return [
            'code' => $inputs['codigo_del_domicilio_fiscal'],
        ];
    }
}