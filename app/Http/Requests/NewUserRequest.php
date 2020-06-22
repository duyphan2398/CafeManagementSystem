<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewUserRequest extends FormRequest
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
            "name"                  => "required",
            "username"              => "required|unique:users|min:1",
            "role"                  => "required|in:Manager,Admin,Employee",
            "email"                 => "required|email",
            "password"              => "required|min:5",
            "passwordConfirm"       => "required|min:5|same:password"
        ];
    }
}
