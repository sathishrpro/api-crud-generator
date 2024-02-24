<?php
namespace App\API\Common\Contracts;

interface ValidationInterface
{
    public function validate(): bool;
}
