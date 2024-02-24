<?php

namespace App\API\V1\Products\Contracts;

interface ProductServiceInterface
{
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function find($id);
    public function delete($id);
    public function all();
    public function paginate($page, $limit);
    public function filter($columnName, $value);
}
