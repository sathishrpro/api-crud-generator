<?php

namespace App\API\V1\Products\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'updated_by' => $this->user()->getKey(),
        ]);
    }

    public function rules()
    {
        return [
            //add rest of the rules here
            'is_active' => 'nullable|bool',
            'updated_by' => 'required|integer',
        ];
    }
}
