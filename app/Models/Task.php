<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Traits\ActionByTrait;
use App\Traits\AssignToTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Task extends Model
{
    use ActionByTrait, AssignToTrait;

    protected $casts = [
        'due_date' => 'datetime',
        "status" => TaskStatus::class,
    ];

    protected $guarded = [];

    protected $with = ["assignTo:id,name"];


    protected static function booted(): void
    {
        static::saving(function (Task $task) {
            $task->logs()->create([
                "title" => $task->title,
                "description" => $task->description,
                "status" => $task->status,
                "due_date" => $task->due_date,
                "action_by" => auth()->id(),
            ]);
        });
    }

    // relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function subTasks()
    {
        return $this->hasMany(SubTask::class, "task_id");
    }

    public function logs()
    {
        return $this->hasMany(TaskLog::class, "task_id")->orderByDesc("created_at");
    }
}
