<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TaskCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'result' => true,
            'message' => "Task list.",
            'status' => Response::HTTP_OK,
            'total' => $this->collection->count(),
            'data' => $this->collection->map(function ($task) {
                return [
                    'id' => $task->id,
                    'title' => $task->title,
                    "description" => $task->description,
                    "create_at" => $task->created_at->format('Y-m-d H:i:s'),
                    "assign_to" => $task->assign_to,
                    "assign_to_name" => $task->assignTo->name ?? "null",
                    "due_date" => $task->due_date->format('Y-m-d H:i:s'),
                    "status" => $task->status->getLabelText(),
                    "subtasks_count" => $task->sub_tasks_count,
                    "sub_tasks" => $task->subTasks,
                    "parent_task" => $task->parentTask,
                ];
            }),
        ];
    }


    // 2nd way
    // public function toArray(Request $request): array
    // {
    //     return [
    //         'result' => true,
    //         'message' => "Task list.",
    //         'status' => Response::HTTP_OK,
    //         'total' => $this->collection->count(),
    //         'data' => $this->collection->map(function ($task) {
    //             return $this->formatTask($task);
    //         }),
    //     ];
    // }

    // protected function formatTask($task)
    // {
    //     $formattedTask = [
    //         'id' => $task->id,
    //         'title' => $task->title,
    //         "description" => $task->description,
    //         "create_at" => $task->created_at?->format('Y-m-d H:i:s'),
    //         "assign_to" => $task->assign_to,
    //         "assign_to_name" => $task->assignTo->name ?? "null",
    //         "due_date" => $task->due_date?->format('Y-m-d H:i:s'),
    //         "status" => $task->status?->getLabelText(),
    //         "subtasks_count" => $task->sub_tasks_count,
    //     ];

    //     if ($task->sub_tasks_count > 0) {
    //         $formattedTask["sub_tasks"] = $task->subTasks->map(function ($subTask) {
    //             return $this->formatTask($subTask);
    //         });
    //     }

    //     if ($task->parentTask) {
    //         $formattedTask["parent_task"] = $this->formatTask($task->parentTask);
    //     }

    //     return $formattedTask;
    // }
}
