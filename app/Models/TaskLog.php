<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{

    protected $casts = [
        "status" => TaskStatus::class,
    ];

    protected $guarded = [];

    // relations
    public function task()
    {
        return $this->belongsTo(Task::class, "task_id");
    }
}
