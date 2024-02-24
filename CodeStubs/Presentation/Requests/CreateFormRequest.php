<?php

namespace <<moduleNameSpace>>\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => true,
            'created_by' => $this->user()->getKey(),
            'updated_by' => $this->user()->getKey(),
        ]);
    }

    public function rules()
    {
        return [
            //add rest of the rules here
            'is_active' => 'bool',
            'created_by' => 'required|integer',
            'updated_by' => 'required|integer',
        ];
    }
}
