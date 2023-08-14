<?php

namespace App\Models;

use App\Traits\ActionByTrait;
use App\Traits\AssignToTrait;
use Illuminate\Database\Eloquent\Model;

class SubTask extends Model
{
    use ActionByTrait, AssignToTrait;

    protected $guarded = [];

    protected $casts = [
        "due_date" => "datetime",
    ];

    // relations
    public function task()
    {
        return $this->belongsTo(Task::class, "task_id");
    }
}
