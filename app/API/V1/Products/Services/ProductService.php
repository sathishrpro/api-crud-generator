<?php
namespace App\API\V1\Products\Services;

use \Exception;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;
use App\API\V1\Products\Contracts\ProductRepositoryInterface;
use App\API\V1\Products\Contracts\ProductServiceInterface;
use App\API\V1\Products\Domain\CreateValidator;
use App\API\V1\Products\Domain\UpdateValidator;


class ProductService implements ProductServiceInterface
{
    private $productRepository;


    public function __construct(ProductRepositoryInterface $productRepository)  {
        $this->productRepository = $productRepository;
    }

    public function create(array $attributes)
    {
        try {
            CreateValidator::validate($attributes);
            return $this->productRepository->create($attributes);
        } catch (ValidationException $ve) {
            throw $ve;
        } catch (\Throwable $e) {
           throw $e;
        }
    }

    public function update($id,array $attributes)
    {
        try {
            $product = $this->productRepository->find($id);
            UpdateValidator::validate($product, $attributes);
            return $this->productRepository->update($product, $attributes);
        }
        catch(ItemNotFoundException $infe)  {
            throw $infe;
        }
        catch (ValidationException $ve) {
            throw $ve;
        }
        catch (\Throwable $e) {
           throw $e;
        }
    }

    public function find($id)
    {
        try {
           return $this->productRepository->find($id);
        }
        catch(ItemNotFoundException $infe)  {
            throw $infe;
        }
        catch (ValidationException $ve) {
            throw $ve;
        } 
        catch (\Throwable $e) {
           throw $e;
        }
    }

    public function delete($id)
    {
        try {
           return $this->productRepository->delete($id);
        }
        catch(ItemNotFoundException $infe)  {
            throw $infe;
        }
        catch (\Throwable $e) {
           throw $e;
        }
    }

    public function all()
    {
        try {
           return $this->productRepository->all();
        }
        catch (\Throwable $e) {
           throw $e;
        }
    }

    public function paginate($page, $itemPerPage)
    {
        try {
           return $this->productRepository->paginate($page, $itemPerPage);
        }
        catch (\Throwable $e) {
           throw $e;
        }
    }

    public function filter($column, $value)
    {
        try {
           return $this->productRepository->filter($column, $value);
        }
        catch (\Throwable $e) {
           throw $e;
        }
    }
}
