<?php

namespace App\API\V1\Products\Config;

use Illuminate\Support\ServiceProvider;
use App\API\V1\Products\Contracts\ProductRepositoryInterface;
use App\API\V1\Products\Contracts\ProductServiceInterface;
use App\API\V1\Products\Repositories\ProductRepository;
use App\API\V1\Products\Services\ProductService;

class ProductServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }
}
