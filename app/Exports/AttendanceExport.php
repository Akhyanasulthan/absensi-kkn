<?php

namespace App\Exports;

use App\Models\Attendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->attendances;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Divisi',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Status',
            'Latitude Masuk',
            'Longitude Masuk',
            'Latitude Pulang',
            'Longitude Pulang',
        ];
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->division,
            $row->date,
            $row->check_in ?? '-',
            $row->check_out ?? '-',
            $row->status,
            $row->check_in_latitude ?? '-',
            $row->check_in_longitude ?? '-',
            $row->check_out_latitude ?? '-',
            $row->check_out_longitude ?? '-',
        ];
    }
}
