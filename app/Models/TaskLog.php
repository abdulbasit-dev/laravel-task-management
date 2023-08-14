<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{

    protected $casts = [
        "status" => TaskStatus::class,
        "due_date" => "datetime",
    ];

    protected $guarded = [];

    // relations
    public function task()
    {
        return $this->belongsTo(Task::class, "task_id");
    }

    public function actionBy()
    {
        return $this->belongsTo(User::class, "action_by");
    }
}
