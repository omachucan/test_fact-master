<?php

namespace App\CoreDevPro\Requests\Api\Transform;

use App\CoreDevPro\Requests\Api\Transform\Common\DocumentVoidedTransform;

class SummaryTransform
{
    public static function transform($inputs)
    {
        $inputs_transform = [
            'date_of_reference' => $inputs['fecha_de_emision_de_documentos'],
            'summary_status_type_id' => $inputs['codigo_tipo_proceso'],
        ];

        if($inputs_transform['summary_status_type_id'] === '3') {
            $inputs_transform['documents'] = DocumentVoidedTransform::transform($inputs);
        }

        return $inputs_transform;
    }
}