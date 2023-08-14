<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubTaskRequest;
use App\Http\Resources\SubTaskResource;
use App\Models\SubTask;
use App\Models\Task;
use Illuminate\Http\Response;

class SubTaskController extends Controller
{
    public function store(SubTaskRequest $request, Task $task)
    {
        $task->subTasks()->create($request->validated());
        return $this->jsonResponse(true, __('Sub Task created successfully!'), Response::HTTP_CREATED);
    }

    public function show(Task $task, SubTask $subtask)
    {
        return new SubTaskResource($subtask);
    }

    public function update(SubTaskRequest $request, Task $task, SubTask $subtask)
    {
        $subtask->update($request->validated());
        return $this->jsonResponse(true, __('Sub Task updated successfully!'), Response::HTTP_OK);
    }

    public function destroy(Task $task, SubTask $subtask)
    {
        $subtask->delete();
        return $this->jsonResponse(true, __('Task deleted successfully!'), Response::HTTP_OK);
    }
}
