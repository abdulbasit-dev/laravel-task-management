<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SubTaskCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($subTask) {
            return [
                'id' => $subTask->id,
                'title' => $subTask->title,
                "description" => $subTask->description,
                "status" => $subTask->status->getLabelText(),
                "assign_to" => $subTask->assign_to,
            ];
        })->toArray();
    }
}
