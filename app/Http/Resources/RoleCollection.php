<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Str;

class RoleCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'result' => true,
            'message' => "Roles list.",
            'status' => Response::HTTP_OK,
            'total' => $this->collection->count(),
            'data' => $this->collection->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    "name_readable" => str_replace("_", " ", Str::title($role->name)),
                    "created_at" => $role->created_at->format('Y-m-d H:i:s'),
                ];
            }),
        ];
    }
}
