<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    public function saving(Task $task): void
    {
        $task->logs()->create([
            "title" => $task->title,
            "description" => $task->description,
            "status" => $task->status,
            "due_date" => $task->due_date,
            "action_by" => auth()->id(),
            "assign_to" => $task->assign_to,
        ]);
    }
}
