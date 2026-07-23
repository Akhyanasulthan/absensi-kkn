<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class WeeklyReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $startDate;
    protected $endDate;
    protected $report;
    protected $iteration = 0;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->generateReport();
    }

    protected function generateReport()
    {
        $this->report = Attendance::whereBetween('date', [$this->startDate, $this->endDate])
            ->orderBy('date', 'desc')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function collection()
    {
        return $this->report;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Nama Mahasiswa',
            'Divisi',
            'Jam Masuk',
            'Jam Pulang',
            'Status'
        ];
    }

    public function map($row): array
    {
        $this->iteration++;
        
        return [
            $this->iteration,
            Carbon::parse($row->date)->format('d/m/Y'),
            $row->name,
            $row->division,
            $row->check_in ? Carbon::parse($row->check_in)->format('H:i:s') : '-',
            $row->check_out ? Carbon::parse($row->check_out)->format('H:i:s') : '-',
            $row->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
