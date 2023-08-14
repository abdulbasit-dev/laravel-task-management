<?php

namespace App\Console\Commands;

use App\Exports\TasksExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;


class ExportTaskCommand extends Command
{
    protected $signature = 'tasks:export';
    protected $description = 'Export tasks to a CSV file';

    public function handle()
    {
        $filename = 'exports/tasks_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        try {
            $path = storage_path('app/' . $filename);
            Excel::store(new TasksExport(), $filename);

            $this->info("Tasks exported to {$path}");
        } catch (\Throwable $exception) {
            $this->error("An error occurred: " . $exception->getMessage());
        }
    }
}
