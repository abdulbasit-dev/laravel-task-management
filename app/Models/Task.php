<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Events\TaskDueDatePassedEvent;
use App\Traits\ActionByTrait;
use App\Traits\AssignToTrait;
use Illuminate\Database\Eloquent\Model;

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
        static::updating(function (Task $task) {
            // save log for task
            $task->logs()->create([
                "title" => $task->title,
                "description" => $task->description,
                "status" => $task->status,
                "due_date" => $task->due_date,
                "action_by" => auth()->id(),
                "assign_to" => $task->assign_to,
            ]);

            // search for task due date passed
            $overDueTasks = Task::query()
                ->where("due_date", "<=", now())
                ->get();

            foreach ($overDueTasks as $overDueTask) {
                // fire event
                TaskDueDatePassedEvent::dispatch($overDueTask);
            }
        });
    }

    // relations
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function subTask()
    {
        return $this->hasMany(Task::class, "task_id");
    }

    public function logs()
    {
        return $this->hasMany(TaskLog::class, "task_id")->orderByDesc("created_at");
    }
}
