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
            'message' => "User details.",
            'status' => Response::HTTP_OK,
            'data' =>[
                'id' => $this->id,
                'title' => $this->title,
                "description" => $this->description,
                "created_at" => $this->created_at->format('Y-m-d H:i:s'),
                "due_date" => $this->due_date->format('Y-m-d H:i:s'),
                "status" => $this->status->getLabelText()
            ]

        ];
    }
}
