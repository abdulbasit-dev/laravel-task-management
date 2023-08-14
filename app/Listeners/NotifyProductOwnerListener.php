<?php

namespace App\Listeners;

use App\Events\TaskDueDatePassedEvent;
use App\Models\User;
use App\Notifications\TaskDeadlinePassedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification as FacadesNotification;

class NotifyProductOwnerListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TaskDueDatePassedEvent $event): void
    {
        $task = $event->task;
        // Send WebSocket alert to the Product Owner (the user who created the task)
        $productOwner = User::find($task->created_by);
        FacadesNotification::send($productOwner, new TaskDeadlinePassedNotification($task->title));
        Log::info("Alert sent to Product Owner for task: {$task->title}");
    }
}
