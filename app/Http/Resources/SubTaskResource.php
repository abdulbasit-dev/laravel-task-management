<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SubTaskResource extends JsonResource
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
            'message' => "Subtask details.",
            'status' => Response::HTTP_OK,
            'data' => [
                'id' => $this->id,
                "task_id" => $this->task_id,
                'title' => $this->title,
                "assign_to" => $this->assign_to,
                "assign_to_name" => $this->assignTo->name ?? null,
                "description" => $this->description,
                "due_date" => $this->due_date->format('Y-m-d H:i:s'),
                "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            ]

        ];
    }
}
