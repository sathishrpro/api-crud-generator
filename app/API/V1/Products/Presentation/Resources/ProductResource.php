<?php
namespace App\API\V1\Products\Presentation\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\API\Auth\Presentation\Resources\UserResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            //rest of the fields here
            'createdBy' => new UserResource($this->createdBy),
            'updatedBy' => new UserResource($this->createdBy),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
