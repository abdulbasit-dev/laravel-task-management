<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    protected $guarded = [];

    // relations
    public function task()
    {
        return $this->belongsTo(Task::class, "task_id");
    }
}
