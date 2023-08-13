<?php

namespace App\Models;

use App\Traits\ActionByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use ActionByTrait, HasFactory;

    protected $guarded = [];

    // relations
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
