<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableUpdateProducts extends FormRequest
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
            'product_list'                  => 'required|array',
            'product_list.*.id'             => 'required|exists:products,id',
            'product_list.*.quantity'       => 'required|numeric',
            'product_list.*.note'           => 'filled',
        ];
    }
}
