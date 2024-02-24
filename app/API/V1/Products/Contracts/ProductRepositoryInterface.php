<?php

namespace App\API\V1\Products\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
 
interface ProductRepositoryInterface
{
    public function create(array $attributes);
    public function update(Model $product, array $attributes);
    public function find($id);
    public function delete($id);
    public function all();
    public function paginate($page = 1, $limit = 10);
    public function filter($columnName, $value, Collection $eloquentCollection = null, $operator = "=");
}