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
        $students = User::where('role', 'user')->orderBy('name', 'asc')->get();
        $attendances = Attendance::whereBetween('date', [$this->startDate, $this->endDate])
            ->get()
            ->groupBy('name');

        $report = collect();
        foreach ($students as $student) {
            $studentAtts = $attendances->get($student->name, collect());
            
            $present = $studentAtts->where('status', 'Present')->count();
            $late = $studentAtts->where('status', 'Late')->count();
            $checkoutOnly = $studentAtts->where('status', 'Checkout Only')->count();
            
            $totalDays = Carbon::parse($this->startDate)->diffInDays(Carbon::parse($this->endDate)) + 1;
            
            $attended = $present + $late + $checkoutOnly;
            $absent = $totalDays - $attended;

            $report->push((object)[
                'name' => $student->name,
                'division' => $student->division,
                'present' => $present,
                'late' => $late,
                'checkout_only' => $checkoutOnly,
                'absent' => $absent,
                'total_attended' => $attended,
                'total_days' => $totalDays,
            ]);
        }
        $this->report = $report;
    }

    public function collection()
    {
        return $this->report;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Divisi',
            'Total Hadir',
            'Total Terlambat',
            'Hanya Pulang',
            'Total Alpa (Tidak Absen)',
            'Jumlah Kehadiran',
            'Total Hari Kerja'
        ];
    }

    public function map($row): array
    {
        $this->iteration++;
        
        return [
            $this->iteration,
            $row->name,
            $row->division,
            $row->present,
            $row->late,
            $row->checkout_only,
            $row->absent,
            $row->total_attended,
            $row->total_days,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
