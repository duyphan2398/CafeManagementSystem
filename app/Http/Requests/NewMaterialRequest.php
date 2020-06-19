<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewMaterialRequest extends FormRequest
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
            'name'      => 'required|string|unique:materials,name',
            'amount'    => 'required|numeric|gt:0',
            'unit'      => 'required|string',
            'note'      => 'string'
        ];
    }
}
