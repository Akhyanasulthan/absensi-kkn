@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.04em;">Dashboard Admin</h1>
    <p style="color: var(--text-muted); font-size: 1rem; margin-top: 0.5rem;">Pantau aktivitas absensi KKN hari ini: <span style="font-weight: 600; color: var(--primary);">{{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }}</span></p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <!-- Stat 1 -->
    <div class="glass-card" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; border-radius: var(--radius-lg); border-left: 4px solid var(--success);">
        <div>
            <p style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Absen Masuk</p>
            <h3 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">{{ $stats['total_check_in'] }}</h3>
        </div>
        <div style="background: linear-gradient(135deg, var(--success-light), #ecfdf5); color: var(--success); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
            <i data-lucide="log-in" style="width: 32px; height: 32px;"></i>
        </div>
    </div>

    <!-- Stat 2 -->
    <div class="glass-card" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; border-radius: var(--radius-lg); border-left: 4px solid var(--primary);">
        <div>
            <p style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Absen Pulang</p>
            <h3 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">{{ $stats['total_check_out'] }}</h3>
        </div>
        <div style="background: linear-gradient(135deg, var(--primary-light), #eef2ff); color: var(--primary); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm);">
            <i data-lucide="log-out" style="width: 32px; height: 32px;"></i>
        </div>
    </div>

    <!-- Stat 3 -->
    <div class="glass-card" style="padding: 1.75rem; display: flex; align-items: center; justify-content: space-between; border-radius: var(--radius-lg); border-left: 4px solid var(--bg-sidebar);">
        <div>
            <p style="font-size: 0.85rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Total Aktivitas</p>
            <h3 style="font-size: 2.5rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; line-height: 1;">{{ $stats['total_attendance'] }}</h3>
        </div>
        <div style="background: linear-gradient(135deg, #f1f5f9, #f8fafc); color: var(--bg-sidebar); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: var(--shadow-sm); border: 1px solid var(--border-color);">
            <i data-lucide="database" style="width: 32px; height: 32px;"></i>
        </div>
    </div>
</div>

<!-- Shortcuts and Activity Row -->
<div style="display: grid; grid-template-columns: 1fr; gap: 2rem;">
    <!-- Latest Scan Logs -->
    <div class="glass-card" style="padding: 0; overflow: hidden;">
        <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid var(--border-color); background: rgba(255,255,255,0.5);">
            <div>
                <h3 style="font-size: 1.35rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.02em;">Log Absensi Hari Ini</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Aktivitas terbaru mahasiswa KKN posko hari ini</p>
            </div>
            <a href="{{ route('admin.logs') }}" class="btn btn-outline">
                Lihat Laporan Lengkap <i data-lucide="arrow-right" style="width: 18px; height: 18px;"></i>
            </a>
        </div>

        <div class="table-container" style="border: none; border-radius: 0; box-shadow: none;">
            <table>
                <thead>
                    <tr>
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
                            <td style="font-weight: 600;">{{ $log->name }}</td>
                            <td>{{ $log->division }}</td>
                            <td>
                                <span class="badge" style="background-color: var(--success-light); color: var(--success);">
                                    {{ $log->check_in ? \Carbon\Carbon::parse($log->check_in)->format('H:i:s') : '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="{{ $log->check_out ? 'background-color: var(--primary-light); color: var(--primary);' : 'background-color: #f1f5f9; color: var(--text-muted);' }}">
                                    {{ $log->check_out ? \Carbon\Carbon::parse($log->check_out)->format('H:i:s') : 'Belum Pulang' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge" style="
                                    @if($log->status === 'Present') background-color: var(--success-light); color: var(--success);
                                    @elseif($log->status === 'Late') background-color: var(--warning-light); color: var(--warning);
                                    @else background-color: var(--danger-light); color: var(--danger); @endif">
                                    {{ $log->status }}
                                </span>
                            </td>
                            <td style="font-family: monospace; font-size: 0.85rem;">
                                @if($log->check_in_latitude)
                                    <a href="https://maps.google.com/?q={{ $log->check_in_latitude }},{{ $log->check_in_longitude }}" target="_blank" style="color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 0.35rem; font-weight: 500;">
                                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i> {{ round($log->check_in_latitude, 5) }}, {{ round($log->check_in_longitude, 5) }}
                                    </a>
                                @else
                                    <span style="color: var(--text-muted);">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                                <i data-lucide="inbox" style="margin: 0 auto 1rem auto; display: block; width: 48px; height: 48px; opacity: 0.5;"></i>
                                <span style="font-size: 1.1rem; font-weight: 500;">Belum ada aktivitas absensi hari ini.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
