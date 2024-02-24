<?php

namespace App\API\Common\Traits;

trait UpdatedByRelationshipTrait
{
    public function updatedBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'updated_by');
    }
}
