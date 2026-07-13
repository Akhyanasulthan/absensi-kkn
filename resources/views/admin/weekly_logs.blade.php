@extends('layouts.app')

@section('title', 'Riwayat Harian')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--bg-sidebar); letter-spacing: -0.02em;">Laporan Absensi Harian</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Data absensi dipisahkan setiap hari untuk bahan evaluasi harian posko KKN.</p>
    </div>
</div>

@if (session('success'))
    <div style="background-color: var(--success-light); color: var(--success-hover); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1.25rem; border-radius: var(--radius-md); font-size: 0.95rem; font-weight: 500; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: var(--shadow-sm);">
        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if ($errors->any())
    <div style="background-color: var(--danger-light); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); padding: 1.25rem; border-radius: var(--radius-md); font-size: 0.95rem; font-weight: 500; margin-bottom: 2rem; box-shadow: var(--shadow-sm);">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Filter and Export Actions -->
<div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-md); margin-bottom: 2rem;">
    <form action="{{ route('admin.logs') }}" method="GET" id="filter-form" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1.5rem; justify-content: space-between;">
        
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-end; flex: 1;">
            <!-- Select Date -->
            <div style="flex: 1; min-width: 280px;">
                <label for="date-select" class="form-label" style="font-weight: 600;">Pilih Tanggal Evaluasi</label>
                <input type="date" name="date" id="date-select" class="form-input" style="background-color: white;" value="{{ $selectedDate }}" max="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}">
            </div>
            
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                <i data-lucide="filter"></i> Terapkan Filter
            </button>
        </div>

        <!-- Export Buttons -->
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <button type="button" onclick="openManualModal()" class="btn btn-primary" style="background-color: var(--bg-sidebar);">
                <i data-lucide="user-plus"></i> Tambah Absen Manual
            </button>
            <a href="{{ route('admin.logs.export.excel', ['date' => $selectedDate]) }}" class="btn btn-success">
                <i data-lucide="file-spreadsheet"></i> Unduh Excel
            </a>
            <a href="{{ route('admin.logs.export.pdf', ['date' => $selectedDate]) }}" class="btn btn-outline" style="border-color: #ef4444; color: #ef4444;">
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
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.1rem;">Menampilkan {{ $attendances->count() }} data pada tanggal {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}</p>
        </div>
    </div>

    <div class="table-container">
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
                                {{ $log->check_in ? 'Hadir - ' . \Carbon\Carbon::parse($log->check_in)->format('H:i:s') : '-' }}
                            </span>
                        </td>
                        <td style="padding: 1rem;">
                            <span style="{{ $log->check_out ? 'background-color: var(--primary-light); color: var(--primary);' : 'background-color: #f1f5f9; color: var(--text-muted);' }} padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.8rem; font-weight: 500;">
                                {{ $log->check_out ? 'Pulang - ' . \Carbon\Carbon::parse($log->check_out)->format('H:i:s') : '-' }}
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
                            Tidak ada data kehadiran mahasiswa pada tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Manual Attendance Modal -->
<div id="manual-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.5); z-index: 50; align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: white; width: 100%; max-width: 500px; border-radius: var(--radius-lg); padding: 2rem; box-shadow: var(--shadow-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 0.5rem;">
                <i data-lucide="user-check" style="color: var(--primary); width: 22px; height: 22px;"></i> Tambah Absen Manual
            </h3>
            <button type="button" onclick="closeManualModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" style="width: 24px; height: 24px;"></i>
            </button>
        </div>

        <form action="{{ route('admin.logs.manual') }}" method="POST">
            @csrf
            
            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="student_id" class="form-label">Mahasiswa</label>
                <select name="student_id" id="student_id" class="form-input" required>
                    <option value="" disabled selected>Pilih Mahasiswa...</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->division }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="date" class="form-label">Tanggal</label>
                <input type="date" name="date" id="date" class="form-input" value="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}" required>
            </div>

            <div class="form-group" style="margin-bottom: 1.25rem;">
                <label for="status" class="form-label">Tipe Absen</label>
                <select name="status" id="status" class="form-input" required>
                    <option value="Present">Masuk</option>
                    <option value="Checkout Only">Pulang</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                <div class="form-group" style="flex: 1;">
                    <label for="check_in" class="form-label">Jam Masuk</label>
                    <input type="time" name="check_in" id="check_in" class="form-input">
                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">Opsional</span>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="check_out" class="form-label">Jam Pulang</label>
                    <input type="time" name="check_out" id="check_out" class="form-input">
                    <span style="font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">Opsional</span>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeManualModal()" class="btn btn-outline" style="flex: 1; justify-content: center;">Batal</button>
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">Simpan Absensi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const manualModal = document.getElementById('manual-modal');
    
    function openManualModal() {
        manualModal.style.display = 'flex';
        // Re-init lucide icons in case they didn't render inside the hidden modal
        if(typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeManualModal() {
        manualModal.style.display = 'none';
    }

    // Close modal when clicking outside
    manualModal.addEventListener('click', function(e) {
        if (e.target === manualModal) {
            closeManualModal();
        }
    });
</script>
@endsection
