<?php

namespace App\API\V1\Products\Infrastructure;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\API\Common\Traits\CreatedByRelationshipTrait;
use App\API\Common\Traits\ScopeActiveTrait;
use App\API\Common\Traits\UpdatedByRelationshipTrait;

class Product extends Model
{
    use HasFactory;
    use ScopeActiveTrait;
    use CreatedByRelationshipTrait;
    use UpdatedByRelationshipTrait;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    protected $guarded = [];
}

