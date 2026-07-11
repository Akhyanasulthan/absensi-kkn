@extends('layouts.app')

@section('title', 'Riwayat Mingguan')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--bg-sidebar); letter-spacing: -0.02em;">Laporan Absensi Mingguan</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Data absensi dipisahkan setiap minggu untuk bahan evaluasi mingguan posko KKN.</p>
    </div>
</div>

<!-- Filter and Export Actions -->
<div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
    <form action="{{ route('admin.logs') }}" method="GET" id="filter-form" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1.5rem; justify-content: space-between;">
        
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-end; flex: 1;">
            <!-- Select Week -->
            <div style="flex: 1; min-width: 280px;">
                <label for="week-select" class="form-label" style="font-weight: 600;">Pilih Evaluasi Evaluasi Mingguan</label>
                <select id="week-select" class="form-input" style="background-color: white;" onchange="updateWeekDates(this)">
                    @foreach ($weeks as $w)
                        <option value="{{ $w['start'] }}|{{ $w['end'] }}" {{ $selectedStart == $w['start'] ? 'selected' : '' }}>
                            {{ $w['label'] }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="start" id="start-date-input" value="{{ $selectedStart }}">
                <input type="hidden" name="end" id="end-date-input" value="{{ $selectedEnd }}">
            </div>
            
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                <i data-lucide="filter"></i> Terapkan Filter
            </button>
        </div>

        <!-- Export Buttons -->
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('admin.logs.export.excel', ['start' => $selectedStart, 'end' => $selectedEnd]) }}" class="btn btn-success">
                <i data-lucide="file-spreadsheet"></i> Unduh Excel
            </a>
            <a href="{{ route('admin.logs.export.pdf', ['start' => $selectedStart, 'end' => $selectedEnd]) }}" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;">
                <i data-lucide="file-text"></i> Unduh PDF
            </a>
        </div>
    </form>
</div>

<!-- Logs Data Table -->
<div class="glass-card" style="padding: 2rem; border-radius: var(--radius-lg);">
    <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
        <div>
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--bg-sidebar);">Data Kehadiran</h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.1rem;">Menampilkan {{ $attendances->count() }} data pada periode {{ \Carbon\Carbon::parse($selectedStart)->translatedFormat('d M Y') }} s.d. {{ \Carbon\Carbon::parse($selectedEnd)->translatedFormat('d M Y') }}</p>
        </div>
    </div>

    <div style="overflow-x: auto; border: 1px solid var(--border-color); border-radius: var(--radius-md); background: white;">
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem;">
            <thead>
                <tr style="background-color: #f8fafc; border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Tanggal</th>
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Nama</th>
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Divisi</th>
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Jam Masuk</th>
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Jam Pulang</th>
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Status</th>
                    <th style="padding: 1rem; font-weight: 600; color: var(--text-muted);">Rincian Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $log)
                    <tr style="border-bottom: 1px solid var(--border-color); transition: background-color 0.15s ease;">
                        <td style="padding: 1rem; color: var(--bg-sidebar); font-weight: 600;">
                            {{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}
                        </td>
                        <td style="padding: 1rem; font-weight: 500; color: var(--text-main);">{{ $log->name }}</td>
                        <td style="padding: 1rem; color: var(--text-muted);">{{ $log->division }}</td>
                        <td style="padding: 1rem;">
                            <span style="background-color: var(--success-light); color: var(--success); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 500;">
                                {{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i:s') : '-' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="{{ $log->check_out ? 'background-color: var(--primary-light); color: var(--primary);' : 'background-color: #f1f5f9; color: var(--text-muted);' }} padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 500;">
                                {{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i:s') : '-' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="display: inline-flex; align-items: center; padding: 0.2rem 0.6rem; font-size: 0.78rem; font-weight: 600; border-radius: 9999px; 
                                @if($log->status === 'Present') background-color: var(--success-light); color: var(--success);
                                @elseif($log->status === 'Late') background-color: var(--warning-light); color: var(--warning);
                                @else background-color: var(--danger-light); color: var(--danger); @endif">
                                {{ $log->status }}
                            </span>
                        </td>
                        <td style="padding: 1rem; font-size: 0.8rem;">
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                @if($log->check_in_latitude)
                                    <a href="https://maps.google.com/?q={{ $log->check_in_latitude }},{{ $log->check_in_longitude }}" target="_blank" style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                                        <i data-lucide="map-pin" style="width: 12px; height: 12px;"></i> Masuk: {{ round($log->check_in_latitude, 4) }}, {{ round($log->check_in_longitude, 4) }}
                                    </a>
                                @endif
                                @if($log->check_out_latitude)
                                    <a href="https://maps.google.com/?q={{ $log->check_out_latitude }},{{ $log->check_out_longitude }}" target="_blank" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 0.25rem;">
                                        <i data-lucide="map-pin" style="width: 12px; height: 12px;"></i> Pulang: {{ round($log->check_out_latitude, 4) }}, {{ round($log->check_out_longitude, 4) }}
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                            <i data-lucide="alert-circle" style="margin: 0 auto 0.5rem auto; display: block; width: 42px; height: 42px;"></i>
                            Tidak ada data kehadiran mahasiswa pada minggu ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function updateWeekDates(selectEl) {
        const val = selectEl.value;
        const dates = val.split('|');
        if (dates.length === 2) {
            document.getElementById('start-date-input').value = dates[0];
            document.getElementById('end-date-input').value = dates[1];
        }
    }
</script>
@endsection
