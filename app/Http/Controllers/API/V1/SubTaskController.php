<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubTaskRequest;
use App\Models\SubTask;
use App\Models\Task;

class SubTaskController extends Controller
{
    public function store(SubTaskRequest $request, Task $task)
    {
        $validated = $request->validated();
        return $validated;
        $task->tasks()->create($request->validated());
        return redirect()->route('admin.checklist_groups.checklists.edit',  [$task->checklist_group_id, $task]);
    }

    public function edit(Task $task, SubTask $subTask)
    {
        return view('admin.tasks.edit', compact('checklist', 'task'));
    }

    public function update(SubTaskRequest $request, Task $task, SubTask $subTask)
    {
        $task->update($request->validated());
        return redirect()->route('admin.checklist_groups.checklists.edit',  [$task->checklist_group_id, $task]);
    }

    public function destroy(Task $task, SubTask $subTask)
    {
        $task->delete();
        return redirect()->route('admin.checklist_groups.checklists.edit',  [$task->checklist_group_id, $task]);
    }
}
