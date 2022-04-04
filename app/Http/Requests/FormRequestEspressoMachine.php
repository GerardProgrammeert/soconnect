<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestEspressoMachine extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'water_container_level' => 'min:0|numeric',
            'beans_container_level' => 'min:0|integer',
            'water_container_capacity' => 'min:0|numeric|gte:water_container_level',
            'beans_container_capacity' => 'min:0|integer|gte:beans_container_level',
        ];
    }
}
