<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TaskController extends Controller
{

    protected $taskAssignmentService;

    public function __construct(TaskAssignmentService $taskAssignmentService)
    {
        $this->taskAssignmentService = $taskAssignmentService;
    }

    public function index(Request $request)
    {
        $this->authorize('view_task');

        $searchParams = $request->all();

        // get search parameters from request
        $id = Arr::get($searchParams, "id", null);
        $title = Arr::get($searchParams, "title", null);
        $description = Arr::get($searchParams, "description", null);
        $assignedTo = Arr::get($searchParams, "assigned_to", null);

        $tasks = Task::query()
            ->with("assignTo:id,name","logs")
            ->when($id, function ($query, $id) {
                return $query->where("id", $id);
            })
            ->when($title, function ($query, $title) {
                return $query->where("title", "LIKE", "%$title%");
            })
            ->when($description, function ($query, $description) {
                return $query->where("description", "LIKE", "%$description%");
            })
            ->when($assignedTo, function ($query, $assignedTo) {
                return $query->whereHas("assignTo", function ($query) use ($assignedTo) {
                    return $query->WhereIn("id", $assignedTo);
                });
            })
            ->withCount("subTasks")
            ->get();

        return new TaskCollection($tasks);
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('add_task');

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $task = Task::create($validated);

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('Task created successfully!'), Response::HTTP_CREATED, $task);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }

    public function show(Task $task)
    {
        $this->authorize('view_task');

        $task->load("subTasks:id,task_id,title,description,due_date");

        return new TaskResource($task);
    }

    public function update(TaskRequest $request, Task $task)
    {
        $this->authorize('edit_task');

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $task->update($validated);

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('Task updated successfully!'), Response::HTTP_OK, $task);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();
        }
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete_task');

        // begin transaction
        DB::beginTransaction();
        try {
            $task->delete();

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('Task deleted successfully!'), Response::HTTP_OK);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }

    public function assignTask(Request $request, Task $task)
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

            $task->update([
                "assign_to" => $request->user_id,
            ]);

            $this->taskAssignmentService->sendAssignmentEmail($task, $assignedToUser->email);

            return $this->jsonResponse(true, __('Task assigned successfully!'), Response::HTTP_OK, $task);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changeStatus(Request $request, Task $task)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            "status" => ['required', 'in:TODO,IN_PROGRESS,READY_FOR_TEST,PO_REVIEW,DONE,REJECTED'],
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(false, __("The given data was invalid."), Response::HTTP_UNPROCESSABLE_ENTITY, null, $validator->errors()->all());
        }

        try {
            // $userRole = auth()->user()->getRoleNames()->first();
            // // transform role name to title case
            // $userRole = str_replace('_', ' ', Str::title($userRole));

            // // Define allowed transitions for each role
            // $allowedTransitions = [
            //     'Developer' => ['TODO' => 'IN_PROGRESS', 'IN_PROGRESS' => 'READY_FOR_TEST'],
            //     'Tester' => ['READY_FOR_TEST' => 'PO_REVIEW'],
            //     'Product Owner' => ['PO_REVIEW' => 'DONE', 'DONE' => 'IN_PROGRESS'],
            // ];

            // if (!array_key_exists($userRole, $allowedTransitions)) {
            //     return $this->jsonResponse(false, __('User role is not allowed to change task status.'), Response::HTTP_FORBIDDEN);
            // }

            // $currentStatus = $task->status;
            // $requestedStatus = $request->status;

            // if (!array_key_exists($currentStatus, $allowedTransitions[$userRole]) || $allowedTransitions[$userRole][$currentStatus] !== $requestedStatus) {
            //     return $this->jsonResponse(false, __('Invalid status transition for the user role.'), Response::HTTP_UNPROCESSABLE_ENTITY);
            // }

            // Update task status
            $task->update([
                'status' => TaskStatus::fromName($request->status),
            ]);

            return $this->jsonResponse(true, __('Task status updated successfully!'), Response::HTTP_OK, $task);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
