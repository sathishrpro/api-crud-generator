<?php
 
namespace App\API\V1\Products\Presentation\Resources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\API\V1\Products\Infrastructure\Product;
use App\API\V1\Products\Presentation\Resources\ProductResource;
use App\Helpers\PaginationHelper;
 
class ProductResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        if($request->has('page')) {
            return PaginationHelper::paginate($this->collection, $request->items_per_page, "products");
        }
        return [
            'products' => $this->collection,
            'meta' => [
                'total_records' => $this->collection->count(),
            ],
        ];
    }
}