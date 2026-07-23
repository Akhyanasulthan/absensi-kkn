@extends('layouts.app')

@section('title', 'Riwayat Harian')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem; color: var(--woody-blue); letter-spacing: 1px; text-shadow: 2px 2px 0 white;">Buku Catatan Harian</h1>
        <p style="color: var(--text-main); font-size: 1.1rem; margin-top: 0.25rem; font-weight: 700;">Data absensi mainan dipisahkan setiap hari untuk bahan evaluasi posko KKN.</p>
    </div>
</div>

@if (session('success'))
    <div style="background-color: var(--buzz-green); color: white; border: 4px solid var(--buzz-green-dark); padding: 1.25rem; border-radius: 16px; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; text-shadow: 1px 1px 0 var(--buzz-green-dark);">
        <i data-lucide="check-circle" style="width: 24px; height: 24px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if ($errors->any())
    <div style="background-color: var(--woody-red); color: white; border: 4px solid var(--woody-red-dark); padding: 1.25rem; border-radius: 16px; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem; text-shadow: 1px 1px 0 var(--woody-red-dark);">
        <ul style="margin: 0; padding-left: 1.5rem;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Filter and Export Actions -->
<div class="toy-card" style="padding: 1.5rem; border-color: var(--woody-brown); margin-bottom: 2rem; background: var(--woody-yellow); border-radius: 20px;">
    <form action="{{ route('admin.logs') }}" method="GET" id="filter-form" style="display: flex; flex-wrap: wrap; align-items: flex-end; gap: 1.5rem; justify-content: space-between;">
        
        <div style="display: flex; flex-wrap: wrap; gap: 1.5rem; align-items: flex-end; flex: 1;">
            <!-- Select Date -->
            <div style="flex: 1; min-width: 240px; max-width: 320px;">
                <label for="date-select" class="toy-label" style="color: var(--woody-brown);">Pilih Tanggal Evaluasi</label>
                <input type="date" name="date" id="date-select" class="toy-input" style="background-color: white; border-color: var(--woody-brown); margin-bottom: 0;" value="{{ $selectedDate }}" max="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}">
            </div>
            
            <button type="submit" class="toy-btn toy-btn-small" style="background: var(--woody-brown); border-color: #5C2E0A; text-shadow: 1px 1px 0 #5C2E0A;">
                <i data-lucide="filter"></i> TERAPKAN FILTER
            </button>
        </div>

        <!-- Export Buttons -->
        <div style="display: flex; flex-wrap: wrap; gap: 0.75rem;">
            <button type="button" onclick="openManualModal()" class="toy-btn toy-btn-small" style="background: var(--woody-blue); border-color: #1D4ED8; text-shadow: 1px 1px 0 #1D4ED8;">
                <i data-lucide="user-plus"></i> ABSEN MANUAL
            </button>
            <a href="{{ route('admin.logs.export.excel', ['date' => $selectedDate]) }}" class="toy-btn toy-btn-small">
                <i data-lucide="file-spreadsheet"></i> EXCEL
            </a>
            <a href="{{ route('admin.logs.export.pdf', ['date' => $selectedDate]) }}" class="toy-btn toy-btn-small toy-btn-danger">
                <i data-lucide="file-text"></i> PDF
            </a>
        </div>
    </form>
</div>

<!-- Logs Data Table -->
<div class="toy-card" style="padding: 0; overflow: hidden; border-color: var(--woody-blue);">
    <div style="padding: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 4px solid var(--woody-blue); background: #EFF6FF;">
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--woody-blue);">Data Kehadiran <span style="color: var(--woody-red);">({{ $attendances->count() }} Data)</span></h3>
            <p style="color: var(--text-muted); font-size: 1rem; margin-top: 0.25rem; font-weight: 700;">Menampilkan log absensi pada tanggal <strong style="color: var(--text-main);">{{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('l, d M Y') }}</strong></p>
        </div>
    </div>

    <div class="table-container" style="border: none; border-radius: 0; box-shadow: none;">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0; text-align: left;">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Divisi</th>
                    <th>Jam Masuk</th>
                    <th>Jam Pulang</th>
                    <th>Status</th>
                    <th>Rincian Lokasi</th>
                    <th style="text-align: right;">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($attendances as $log)
                    <tr>
                        <td style="color: var(--text-muted); font-weight: 800;">{{ $loop->iteration }}</td>
                        <td style="font-weight: 800; color: var(--woody-brown);">
                            {{ \Carbon\Carbon::parse($log->date)->translatedFormat('d M Y') }}
                        </td>
                        <td style="font-weight: 800; color: var(--woody-blue);">{{ $log->name }}</td>
                        <td style="font-weight: 700;">{{ $log->division }}</td>
                        <td style="white-space: nowrap;">
                            <span class="badge badge-success">
                                {{ $log->check_in ? 'Hadir - ' . \Carbon\Carbon::parse($log->check_in)->format('H:i:s') : '-' }}
                            </span>
                        </td>
                        <td style="white-space: nowrap;">
                            <span class="badge {{ $log->check_out ? 'badge-success' : 'badge-warning' }}">
                                {{ $log->check_out ? 'Pulang - ' . \Carbon\Carbon::parse($log->check_out)->format('H:i:s') : '-' }}
                            </span>
                        </td>
                        <td style="white-space: nowrap;">
                            <span class="badge 
                                @if($log->status === 'Present') badge-success 
                                @elseif($log->status === 'Late') badge-warning 
                                @elseif($log->status === 'Belum Absen') badge-secondary
                                @else badge-danger @endif"
                                @if($log->status === 'Belum Absen') style="background: #9CA3AF; color: white;" @endif>
                                {{ $log->status }}
                            </span>
                        </td>
                        <td style="font-size: 0.85rem; font-family: monospace;">
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                @if($log->check_in_latitude)
                                    <a href="https://maps.google.com/?q={{ $log->check_in_latitude }},{{ $log->check_in_longitude }}" target="_blank" style="color: var(--woody-red); text-decoration: none; display: flex; align-items: center; gap: 0.35rem; font-weight: 700;">
                                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i> Masuk: {{ round($log->check_in_latitude, 4) }}, {{ round($log->check_in_longitude, 4) }}
                                    </a>
                                @endif
                                @if($log->check_out_latitude)
                                    <a href="https://maps.google.com/?q={{ $log->check_out_latitude }},{{ $log->check_out_longitude }}" target="_blank" style="color: var(--text-muted); text-decoration: none; display: flex; align-items: center; gap: 0.35rem; font-weight: 700;">
                                        <i data-lucide="map-pin" style="width: 14px; height: 14px;"></i> Pulang: {{ round($log->check_out_latitude, 4) }}, {{ round($log->check_out_longitude, 4) }}
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td style="white-space: nowrap; text-align: right;">
                            @if($log->status === 'Belum Absen')
                                <form action="{{ route('admin.logs.manual') }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $log->student_id }}">
                                    <input type="hidden" name="date" value="{{ $log->date }}">
                                    <input type="hidden" name="status" value="Present">
                                    <input type="hidden" name="check_in" value="08:00">
                                    <button type="submit" class="toy-btn toy-btn-small" style="background: var(--buzz-green); border-color: var(--buzz-green-dark); padding: 0.25rem 0.5rem; font-size: 0.75rem; text-shadow: none;">Hadirkan</button>
                                </form>
                            @elseif(!$log->check_out && $log->check_in)
                                <form action="{{ route('admin.logs.manual') }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $log->student_id }}">
                                    <input type="hidden" name="date" value="{{ $log->date }}">
                                    <input type="hidden" name="status" value="Checkout Only">
                                    <input type="hidden" name="check_out" value="16:00">
                                    <button type="submit" class="toy-btn toy-btn-small" style="background: var(--woody-yellow); color: var(--woody-brown); border-color: var(--woody-brown); padding: 0.25rem 0.5rem; font-size: 0.75rem; text-shadow: none;">Pulangkan</button>
                                </form>
                                <form action="{{ route('admin.logs.cancel') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin membatalkan absen masuk? Data kehadiran hari ini akan dihapus.');">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $log->student_id }}">
                                    <input type="hidden" name="date" value="{{ $log->date }}">
                                    <input type="hidden" name="type" value="check_in">
                                    <button type="submit" class="toy-btn toy-btn-small toy-btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; text-shadow: none; margin-left: 0.25rem;">Batal Hadir</button>
                                </form>
                            @else
                                <form action="{{ route('admin.logs.cancel') }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin membatalkan absen pulang? Jam pulang akan dihapus.');">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $log->student_id }}">
                                    <input type="hidden" name="date" value="{{ $log->date }}">
                                    <input type="hidden" name="type" value="check_out">
                                    <button type="submit" class="toy-btn toy-btn-small toy-btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; text-shadow: none;">Batal Pulang</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
                            <i data-lucide="alert-circle" style="margin: 0 auto 1rem auto; display: block; width: 64px; height: 64px; opacity: 0.3; color: var(--woody-red);"></i>
                            <span style="font-family: var(--font-heading); font-size: 1.2rem;">Tidak ada data kehadiran mainan.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Manual Attendance Modal -->
<div id="manual-modal" style="display: none; position: fixed; inset: 0; background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); z-index: 1100; align-items: center; justify-content: center; padding: 1rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="toy-card" style="background: white; width: 100%; max-width: 500px; padding: 2.5rem; transform: scale(0.95); transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); border-color: var(--buzz-purple);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 4px dashed var(--woody-yellow); padding-bottom: 1rem;">
            <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--buzz-purple); display: flex; align-items: center; gap: 0.6rem;">
                <i data-lucide="user-check" style="width: 24px; height: 24px;"></i> Tambah Absen Manual
            </h3>
            <button type="button" onclick="closeManualModal()" style="background: none; border: none; color: var(--woody-red); cursor: pointer; transform: scale(1.2);">
                <i data-lucide="x" style="width: 24px; height: 24px;"></i>
            </button>
        </div>

        <form action="{{ route('admin.logs.manual') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.25rem;">
                <label for="student_id" class="toy-label">Mainan</label>
                <select name="student_id" id="student_id" class="toy-input" required>
                    <option value="" disabled selected>Pilih Mainan...</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->division }})</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="date" class="toy-label">Tanggal</label>
                <input type="date" name="date" id="date" class="toy-input" value="{{ \Carbon\Carbon::now('Asia/Jakarta')->toDateString() }}" required>
            </div>

            <div style="margin-bottom: 1.25rem;">
                <label for="status" class="toy-label">Tipe Absen</label>
                <select name="status" id="status" class="toy-input" required>
                    <option value="Present">Masuk</option>
                    <option value="Checkout Only">Pulang</option>
                </select>
            </div>

            <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                <div style="flex: 1;">
                    <label for="check_in" class="toy-label">Jam Masuk</label>
                    <input type="time" name="check_in" id="check_in" class="toy-input" style="margin-bottom: 0;">
                    <span style="font-weight: 700; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">Opsional</span>
                </div>
                <div style="flex: 1;">
                    <label for="check_out" class="toy-label">Jam Pulang</label>
                    <input type="time" name="check_out" id="check_out" class="toy-input" style="margin-bottom: 0;">
                    <span style="font-weight: 700; font-size: 0.75rem; color: var(--text-muted); margin-top: 0.25rem; display: block;">Opsional</span>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="button" onclick="closeManualModal()" class="toy-btn" style="flex: 1; background: #9CA3AF; border-color: #4B5563; text-shadow: none;">BATAL</button>
                <button type="submit" class="toy-btn" style="flex: 1;">SIMPAN ABSENSI</button>
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
        setTimeout(() => {
            manualModal.style.opacity = '1';
            manualModal.querySelector('.toy-card').style.transform = 'scale(1)';
        }, 10);
        // Re-init lucide icons in case they didn't render inside the hidden modal
        if(typeof lucide !== 'undefined') lucide.createIcons();
    }

    function closeManualModal() {
        manualModal.style.opacity = '0';
        manualModal.querySelector('.toy-card').style.transform = 'scale(0.95)';
        setTimeout(() => {
            manualModal.style.display = 'none';
        }, 300);
    }

    // Close modal when clicking outside
    manualModal.addEventListener('click', function(e) {
        if (e.target === manualModal) {
            closeManualModal();
        }
    });
</script>
@endsection
