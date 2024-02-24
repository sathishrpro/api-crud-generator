<?php
namespace App\API\Common\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

trait CustomFormRequestErrorResponse
{
    protected function failedValidation(Validator $validator)
    {
        $message = (method_exists($this, 'message'))
        ? $this->container->call([$this, 'message'])
        : 'The given input is invalid.';
        throw new HttpResponseException(response()->json([
        'errors' => $validator->errors(),
        'message' => $message,
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}
