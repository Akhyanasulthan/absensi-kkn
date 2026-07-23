@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--woody-blue); letter-spacing: 1px; text-shadow: 2px 2px 0 white;">Markas Besar Admin</h1>
    <p style="color: var(--text-main); font-size: 1.1rem; margin-top: 0.5rem; font-weight: 700;">Pantau aktivitas absensi KKN hari ini: <span style="color: var(--woody-red); text-shadow: 1px 1px 0 white;">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span></p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Stat 1 (Green Block) -->
    <div class="toy-card" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; position: relative; border-color: var(--buzz-green-dark); background: #DCFCE7;">
        <div>
            <p style="font-size: 0.9rem; font-family: var(--font-heading); color: var(--buzz-green-dark); text-transform: uppercase; letter-spacing: 1px;">Absen Masuk</p>
            <h3 style="font-size: 3rem; font-weight: 900; color: #14532D; margin-top: 0.5rem; line-height: 1;">{{ $stats['total_check_in'] }}</h3>
        </div>
        <div style="background: var(--buzz-green); color: white; width: 70px; height: 70px; border-radius: 16px; border: 4px solid var(--buzz-green-dark); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 0 var(--buzz-green-dark);">
            <i data-lucide="log-in" style="width: 36px; height: 36px;"></i>
        </div>
    </div>

    <!-- Stat 2 (Red Block) -->
    <div class="toy-card" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; position: relative; border-color: var(--woody-red-dark); background: #FEE2E2;">
        <div>
            <p style="font-size: 0.9rem; font-family: var(--font-heading); color: var(--woody-red-dark); text-transform: uppercase; letter-spacing: 1px;">Absen Pulang</p>
            <h3 style="font-size: 3rem; font-weight: 900; color: #7F1D1D; margin-top: 0.5rem; line-height: 1;">{{ $stats['total_check_out'] }}</h3>
        </div>
        <div style="background: var(--woody-red); color: white; width: 70px; height: 70px; border-radius: 16px; border: 4px solid var(--woody-red-dark); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 0 var(--woody-red-dark);">
            <i data-lucide="log-out" style="width: 36px; height: 36px;"></i>
        </div>
    </div>

    <!-- Stat 3 (Blue Block) -->
    <div class="toy-card" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; position: relative; border-color: #1E3A8A; background: #DBEAFE;">
        <div>
            <p style="font-size: 0.9rem; font-family: var(--font-heading); color: #1E3A8A; text-transform: uppercase; letter-spacing: 1px;">Total Aktivitas</p>
            <h3 style="font-size: 3rem; font-weight: 900; color: #1E3A8A; margin-top: 0.5rem; line-height: 1;">{{ $stats['total_attendance'] }}</h3>
        </div>
        <div style="background: var(--woody-blue); color: white; width: 70px; height: 70px; border-radius: 16px; border: 4px solid #1E3A8A; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 0 #1E3A8A;">
            <i data-lucide="database" style="width: 36px; height: 36px;"></i>
        </div>
    </div>
</div>

<!-- Shortcuts and Activity Row -->
<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <!-- Latest Scan Logs -->
    <div class="toy-card" style="padding: 0; overflow: hidden;">
        <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 4px solid var(--woody-blue); background: #EFF6FF;">
            <div>
                <h3 style="font-size: 1.5rem; font-family: var(--font-heading); color: var(--woody-blue); letter-spacing: 1px;">Log Pemain Hari Ini</h3>
                <p style="color: var(--text-muted); font-size: 1rem; margin-top: 0.25rem; font-weight: 700;">Aktivitas terbaru mainan KKN posko hari ini</p>
            </div>
            <a href="{{ route('admin.logs') }}" class="toy-btn toy-btn-small">
                Lihat Lengkap <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </a>
        </div>

        <div class="table-container" style="border: none; border-radius: 0; box-shadow: none;">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>Divisi</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Status</th>
                        <th>Koordinat Masuk</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stats['latest_activity'] as $log)
                        <tr>
                            <td style="color: var(--text-muted); font-weight: 800;">{{ $loop->iteration }}</td>
                            <td style="font-weight: 800; color: var(--woody-blue);">{{ $log->name }}</td>
                            <td>{{ $log->division }}</td>
                            <td style="white-space: nowrap;">
                                <span class="badge badge-success">
                                    {{ $log->check_in ? 'Hadir - ' . \Carbon\Carbon::parse($log->check_in)->format('H:i:s') : '-' }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="badge {{ $log->check_out ? 'badge-success' : 'badge-warning' }}">
                                    {{ $log->check_out ? 'Pulang - ' . \Carbon\Carbon::parse($log->check_out)->format('H:i:s') : 'Belum Pulang' }}
                                </span>
                            </td>
                            <td style="white-space: nowrap;">
                                <span class="badge 
                                    @if($log->status === 'Present') badge-success 
                                    @elseif($log->status === 'Late') badge-warning 
                                    @else badge-danger @endif">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td style="font-family: monospace; font-size: 0.9rem;">
                                @if($log->check_in_latitude)
                                    <a href="https://maps.google.com/?q={{ $log->check_in_latitude }},{{ $log->check_in_longitude }}" target="_blank" style="color: var(--woody-red); text-decoration: none; display: flex; align-items: center; gap: 0.35rem; font-weight: 700;">
                                        <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i> {{ round($log->check_in_latitude, 5) }}, {{ round($log->check_in_longitude, 5) }}
                                    </a>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                                <i data-lucide="inbox" style="margin: 0 auto 1rem auto; display: block; width: 64px; height: 64px; opacity: 0.3; color: var(--woody-blue);"></i>
                                <span style="font-size: 1.2rem; font-family: var(--font-heading);">Tidak ada mainan yang absen hari ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
