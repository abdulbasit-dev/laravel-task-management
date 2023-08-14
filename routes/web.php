<?php

use Illuminate\Support\Facades\Route;
use App\Exports\TasksExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $filename = 'tasks_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

    // Excel::store(new TasksExport(), $filename);

    // $this->info("Tasks exported to {$filename}");
    // return Excel::download(new TasksExport(), $filename);

    return view('welcome');
});
