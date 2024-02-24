<?php

namespace App\API\Auth\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\API\Common\Traits\CustomFormRequestErrorResponse;

class LoginFormRequest extends FormRequest
{
    use CustomFormRequestErrorResponse;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required',
            'password.requrired' => 'Password is required'
        ];
    }
}
