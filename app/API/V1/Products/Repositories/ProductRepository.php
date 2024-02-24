<?php
namespace App\API\V1\Products\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use App\API\V1\Products\Infrastructure\Product;
use App\API\V1\Products\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $attributes)
    {
        return Product::create($attributes);
    }

    public function update(Model $product, array $attributes)
    {
        $product->update($attributes);
        return $product->fresh(); //refresh() also could be used
    }

    public function find($id)
    {
        return Product::findOrFail($id);
    }

    public function delete($id)
    {
        return Product::findOrFail($id)->delete();
    }

    public function all()
    {
        return Product::all();
    }

    public function paginate($page = 1, $limit = 10)
    {
        $page =  (empty($page) || $page < 1) ? 1 : $page;
        $limit = (empty($limit) || $limit > 100) ? 10 : $limit;
        return Product::limit($limit)->offset(($page - 1) * $limit)->get();
    }

    public function filter($column, $value, Collection $eloquentCollection = null, $operator = "=")
    {
        if ($eloquentCollection) {
            if (is_array($value)) {
                return $eloquentCollection->whereIn($column, $value)->all();
            }
            return $eloquentCollection->where($column, $operator, $value)->all();
        }
        if (is_array($value)) {
            return Product::whereIn($column, $value)->get();
        } 
        return Product::where($column, $operator, $value)->get();
    }
}
