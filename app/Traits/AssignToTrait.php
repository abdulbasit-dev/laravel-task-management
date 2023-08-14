<?php

namespace App\Traits;

use App\Models\User;

trait AssignToTrait
{
    public function assignTo()
    {
        return $this->belongsTo(User::class, "assign_to");
    }
}
