<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PriceListRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        
        return [
            'type' => [
                'required',
            ],
            'name' => [
                'required'
            ],
            'total' => [
                'numeric'
            ]
        ];
    }
}