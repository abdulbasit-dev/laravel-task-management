<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubTaskRequest;
use App\Http\Resources\SubTaskResource;
use App\Models\SubTask;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class SubTaskController extends Controller
{
    protected $taskAssignmentService;

    public function __construct(TaskAssignmentService $taskAssignmentService)
    {
        $this->taskAssignmentService = $taskAssignmentService;
    }

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

    public function assignSubTask(Request $request, Task $task, SubTask $subtask)
    {
        //validation
        $validator = Validator::make($request->all(), [
            "user_id" => ['required', 'exists:users,id'],
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(false, __("The given data was invalid."), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        try {
            $user = auth()->user();
            $assignedToUser = User::find($request->user_id);

            // assignTo user should not be same as the authenticated user and and should not be a product owner
            if ($user->id === $assignedToUser->id || $assignedToUser->hasRole('product_owner')) {
                return $this->jsonResponse(false, __('User is not allowed to assign task.'), Response::HTTP_FORBIDDEN);
            }

            $subtask->update([
                "assign_to" => $request->user_id,
            ]);
            $this->taskAssignmentService->sendAssignmentEmail($task, $assignedToUser->email);


            return $this->jsonResponse(true, __('Task assigned successfully!'), Response::HTTP_OK);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
