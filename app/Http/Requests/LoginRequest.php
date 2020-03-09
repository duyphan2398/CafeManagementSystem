<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
    public function messages()
    {
        return [
          'user.required' => 'Please enter the username',
          'password.required' => 'Please enter the password'
        ];
    }

    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required'
        ];
    }
}
