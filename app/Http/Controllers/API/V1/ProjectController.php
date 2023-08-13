<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\ProjectCollection;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        $this->authorize('view_project');

        $projects = Project::all();

        return new ProjectCollection($projects);
    }

    public function store(TaskRequest $request)
    {
        $this->authorize('add_project');

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->validated();

            $project = Project::create($validated);

            return $project;

            // commit transaction
            // DB::commit();

            return $this->jsonResponse(true, __('Task created successfully!'), Response::HTTP_CREATED, $project);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }

    public function show(Task $project)
    {
        $this->authorize('view_project');

        return new ProjectResource($task);
    }

    public function update(TaskRequest $request, Project $project)
    {
        $this->authorize('edit_project');

        // begin transaction
        DB::beginTransaction();
        try {
            $validated = $request->safe()->except(['role']);

            $project->update($validated);

            $project->syncRoles($request->role);

            // commit transaction
            DB::commit();

            return $this->jsonResponse(true, __('Task updated successfully!'), Response::HTTP_OK, $project);
        } catch (\Throwable $th) {
            // rollback transaction
            DB::rollBack();

            throw $th;
        }
    }

    public function destroy(Task $project)
    {
        $this->authorize('delete_project');

        // begin transaction
        DB::beginTransaction();
        try {
            $project->delete();

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
