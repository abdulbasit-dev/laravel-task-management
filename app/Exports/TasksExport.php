<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TasksExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize, WithStyles
{
    use Exportable;

    public function collection()
    {
        // Return the data you want to export
        return Task::all();
    }

    public function map($task): array
    {
        // Define how each row should be mapped
        return [
            $task->id,
            $task->title,
            $task->description,
            $task->status->name,
            $task->assign_to ?? null,
            $task->assignTo->name ?? "Not Assigned",
        ];
    }

    public function headings(): array
    {
        // Define the headings for the exported file
        return [
            'ID',
            'Title',
            'Description',
            "Status",
            "Assigned To Id",
            "Assigned To Name",
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row (header)
            1 => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['argb' => 'ADD8E6'], // Light blue background color
                ],
            ],
        ];
    }


}
