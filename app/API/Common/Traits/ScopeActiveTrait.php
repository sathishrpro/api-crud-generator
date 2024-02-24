<?php
namespace App\API\Common\Traits;

trait ScopeActiveTrait
{
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
