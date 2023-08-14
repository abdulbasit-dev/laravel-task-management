<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TasksImport;
use Exception;

class ImportTasksCommand extends Command
{
    protected $signature = 'tasks:import';
    protected $description = 'Import tasks from a CSV file';

    public function handle()
    {
        try {
            // Define the path to the CSV template file
            $templateFilePath = storage_path('app/task_import_template.csv');

            // check if the file exists
            if (!file_exists($templateFilePath)) {
                $this->error('The template file does not exist!');
                return;
            }

            // Import the data from the CSV file using the TasksImport class
            Excel::import(new TasksImport, $templateFilePath);

            $this->info('Tasks imported successfully!');
        } catch (Exception $e) {
            $this->error('An error occurred during the import: ' . $e->getMessage());
        }
    }
}
