<?php

namespace App\API\Common\Traits;

trait CreatedByRelationshipTrait
{
    public function createdBy()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }
}
