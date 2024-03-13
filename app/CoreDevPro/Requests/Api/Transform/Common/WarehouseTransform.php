<?php

namespace App\CoreDevPro\Requests\Api\Transform\Common;

class WarehouseTransform
{
    public static function transform($inputs)
    {
        return [
            'description' => $inputs['nombre_almacen'],
        ];
    }
}