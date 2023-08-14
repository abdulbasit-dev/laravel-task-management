<?php

namespace App\Services;

use App\Mail\TaskAssigned;
use Illuminate\Support\Facades\Mail;
use App\Models\Task;

class TaskAssignmentService
{
    public function sendAssignmentEmail(Task $task, $assignToEmail)
    {
        Mail::to($assignToEmail)->later(now()->addMinutes(1), new TaskAssigned($task));
        // Mail::to($assignToEmail)->queue( new TaskAssigned($task));
    }
}
