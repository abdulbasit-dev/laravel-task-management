<?php

namespace App\Imports;

use App\Enums\TaskStatus;
use App\Models\Project;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Task;
use Illuminate\Support\Facades\Log;


// class TasksImport implements ToModel, WithStartRow, WithChunkReading, WithHeadingRow
class TasksImport implements ToModel, WithStartRow, WithChunkReading
{

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function model(array $row)
    {
        try {
            return new Task([
                "title" => $row[1],
                "description" => $row[2],
                "project_id" => Project::inRandomOrder()->first()->id,
                "status" => TaskStatus::fromName($row[3]),
                "assign_to" => $row[4],
                "due_date" => now()->addDays(rand(1, 30)),
            ]);
        } catch (Exception $e) {
            Log::error('An error occurred while creating the task: ' . $e->getMessage());
            return null;
        }
    }
}
