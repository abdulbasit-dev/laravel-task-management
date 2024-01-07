<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Events\TaskDueDatePassedEvent;
use App\Traits\ActionByTrait;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use ActionByTrait;

    protected $casts = [
        'due_date' => 'datetime',
        "status" => TaskStatus::class,
    ];

    protected $guarded = [];

    // relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignTo()
    {
        return $this->belongsTo(User::class, "assign_to");
    }

    public function subTasks()
    {
        return $this->hasMany(Task::class, "task_id");
    }
}
