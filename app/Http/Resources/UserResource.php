<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserResource extends JsonResource
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
                'name' => $this->name,
                "role" => str_replace("_", " ", Str::title($this->getRoleNames()->first())),
                'email' => $this->email,
                "task_count" => $this->tasks->count(),
                "created_at" => $this->created_at->format('Y-m-d H:i:s'),
            ]

        ];
    }
}
