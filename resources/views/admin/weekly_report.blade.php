@extends('layouts.app')

@section('title', 'Laporan Mingguan')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <h1 style="font-family: var(--font-heading); color: var(--woody-blue); font-size: 2.5rem; text-shadow: 2px 2px 0 white;">Laporan Kehadiran</h1>
    
    <!-- Filter and Export Actions -->
    <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <!-- Date Filter Form -->
        <form action="{{ route('admin.report') }}" method="GET" style="display: flex; gap: 0.5rem; align-items: center; background: white; padding: 0.5rem; border-radius: 12px; border: 3px solid var(--woody-blue); box-shadow: 4px 4px 0 rgba(59, 130, 246, 0.2);">
            <div>
                <label for="start_date" style="font-size: 0.8rem; font-weight: bold; color: var(--text-muted); display: block;">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="toy-input" style="padding: 0.4rem; margin-bottom: 0; border-width: 2px; font-size: 0.9rem;" required>
            </div>
            <span style="font-weight: bold; color: var(--woody-blue); margin-top: 1rem;">-</span>
            <div>
                <label for="end_date" style="font-size: 0.8rem; font-weight: bold; color: var(--text-muted); display: block;">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="toy-input" style="padding: 0.4rem; margin-bottom: 0; border-width: 2px; font-size: 0.9rem;" required>
            </div>
            <button type="submit" class="toy-btn toy-btn-small" style="margin-top: 1rem; padding: 0.5rem 1rem;">
                <i data-lucide="filter" style="width: 16px; height: 16px;"></i> Filter
            </button>
        </form>

        <!-- Export Buttons -->
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('admin.report.export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="toy-btn toy-btn-small" style="background: var(--buzz-green); border-color: var(--buzz-green-dark); margin-top: 1rem; padding: 0.5rem 1rem;">
                <i data-lucide="file-spreadsheet" style="width: 16px; height: 16px;"></i> Excel
            </a>
        </div>
    </div>
</div>

@if(session('error'))
    <div style="background: #FEE2E2; color: #991B1B; border: 3px solid #F87171; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; font-weight: bold;">
        {{ session('error') }}
    </div>
@endif

<div class="toy-card">
    <div style="margin-bottom: 1rem; font-weight: bold; color: var(--text-muted);">
        Menampilkan laporan dari <span style="color: var(--woody-blue);">{{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y') }}</span> sampai <span style="color: var(--woody-blue);">{{ \Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y') }}</span>
    </div>
    
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Divisi</th>
                    <th style="text-align: center;">Hadir</th>
                    <th style="text-align: center;">Terlambat</th>
                    <th style="text-align: center;">Pulang Saja</th>
                    <th style="text-align: center;">Alpa</th>
                    <th style="text-align: center;">Total Kehadiran</th>
                </tr>
            </thead>
            <tbody>
                @forelse($report as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div style="font-weight: 900; color: var(--woody-blue);">{{ $row->name }}</div>
                        </td>
                        <td>{{ $row->division }}</td>
                        <td style="text-align: center;">
                            <span class="badge badge-success" style="font-size: 0.85rem; padding: 0.2rem 0.6rem;">{{ $row->present }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-warning" style="font-size: 0.85rem; padding: 0.2rem 0.6rem;">{{ $row->late }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge" style="background: #94A3B8; font-size: 0.85rem; padding: 0.2rem 0.6rem;">{{ $row->checkout_only }}</span>
                        </td>
                        <td style="text-align: center;">
                            <span class="badge badge-danger" style="font-size: 0.85rem; padding: 0.2rem 0.6rem;">{{ $row->absent }}</span>
                        </td>
                        <td style="text-align: center; font-weight: 900; color: var(--woody-blue); font-size: 1.1rem;">
                            {{ $row->total_attended }} / {{ $row->total_days }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 3rem;">
                            <i data-lucide="folder-open" style="width: 48px; height: 48px; color: var(--text-muted); opacity: 0.5; margin-bottom: 1rem;"></i>
                            <div style="color: var(--text-muted); font-family: var(--font-heading); font-size: 1.2rem;">Belum ada data mahasiswa.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
