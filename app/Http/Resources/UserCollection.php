<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'result' => true,
            'message' => "Users list.",
            'status' => Response::HTTP_OK,
            'total' => $this->collection->count(),
            'data' => $this->collection->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    "role" => str_replace("_", " ", Str::title($user->getRoleNames()->first())),
                    'email' => $user->email,
                    "task_count" => $user->tasks->count(),
                    "created_at" => $user->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
