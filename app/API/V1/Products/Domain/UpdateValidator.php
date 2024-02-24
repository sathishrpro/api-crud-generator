<?php

namespace App\API\V1\Products\Domain;

use App\API\V1\Products\Infrastructure\Product;
use Illuminate\Validation\ValidationException;

class UpdateValidator
{
    public static function validate(Product $product, $attributes): void
    {
        // Implement your business logic validations here
        // This can include simple or complex logic
        // For example:
        // if ($someCondition) {
        //     throw new ValidationException('Business logic validation failed: Invalid data provided.');
        // }

        // Replace the above example condition with your actual business logic validation condition
        // If the condition evaluates to false, throw a ValidationException
    }
}
