<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index()
    {
        $this->authorize('view_task');

        $tasks = Task::with("assignTo:id,name")->get();

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
}
