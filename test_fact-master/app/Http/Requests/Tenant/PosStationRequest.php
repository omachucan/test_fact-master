<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PosStationRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }
 
    public function rules()
    {
        return [
            'name' => [
                'required',
            ]
        ];
    }
}