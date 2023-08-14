<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'result' => true,
            'message' => "Task details.",
            'status' => Response::HTTP_OK,
            'data' => [
                'id' => $this->id,
                'title' => $this->title,
                "description" => $this->description,
                "assign_to" => $this->assign_to,
                "assign_to_name" => $this->assignTo->name ?? null,
                "created_at" => $this->created_at->format('Y-m-d H:i:s'),
                "due_date" => $this->due_date->format('Y-m-d H:i:s'),
                "status" => $this->status->name,
                "subtasks_count" => $this->subTasks->count(),
                "subtasks" => $this->subTasks->map(function ($subTask) {
                    return [
                        'id' => $subTask->id,
                        'title' => $subTask->title,
                        "description" => $subTask->description,
                        "due_date" => $subTask->due_date->format('Y-m-d H:i:s'),
                    ];
                }),
            ]

        ];
    }
}
