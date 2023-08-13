<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function __invoke(Request $request)
    {
        $statuses = TaskStatus::cases();

        $statusArray = [];

        foreach ($statuses as $status) {
            // Create an array for each status with 'id' and 'name' keys
            $statusArray[] = [
                'id' => $status->value,
                'name' => $status->name,
            ];
        }
        return response()->json([
            'result' => true,
            'message' => __('Task statuses list.'),
            'status' => 200,
            'data' => $statusArray,
        ]);
    }
}
