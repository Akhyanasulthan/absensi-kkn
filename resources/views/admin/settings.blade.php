@extends('layouts.app')

@section('title', 'Pengaturan Posko')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--woody-blue); letter-spacing: 1px; text-shadow: 2px 2px 0 white;">Pengaturan Markas</h1>
    <p style="color: var(--text-main); font-size: 1.1rem; margin-top: 0.5rem; font-weight: 700;">Kelola lokasi geofence markas, jarak radius, serta jam operasional misi.</p>
</div>

@if (session('success'))
    <div style="background-color: var(--buzz-green); color: white; border: 4px solid var(--buzz-green-dark); padding: 1.25rem; border-radius: 16px; font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; text-shadow: 1px 1px 0 var(--buzz-green-dark);">
        <i data-lucide="check-circle" style="width: 24px; height: 24px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div class="toy-card" style="max-width: 840px; padding: 2.5rem; border-color: var(--woody-brown);">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--woody-brown); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 4px dashed var(--woody-yellow); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="info" style="width: 24px; height: 24px;"></i> Informasi Umum
        </h3>

        <div style="margin-bottom: 2.5rem;">
            <label for="kkn_name" class="toy-label">Nama Markas / Identitas Pasukan</label>
            <input type="text" name="kkn_name" id="kkn_name" class="toy-input" value="{{ old('kkn_name', $settings['kkn_name']) }}" placeholder="Contoh: KKN Posko Desa Sukamaju" required>
            @error('kkn_name') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
        </div>

        <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--woody-brown); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 4px dashed var(--woody-yellow); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="map-pin" style="width: 24px; height: 24px;"></i> Koordinat Geofence & Radius
        </h3>

        <div style="background-color: #DBEAFE; color: var(--woody-blue); border: 3px solid var(--woody-blue); padding: 1.25rem; border-radius: 12px; font-size: 0.95rem; font-weight: 700; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 0.75rem; line-height: 1.5;">
            <i data-lucide="info" style="width: 24px; height: 24px; flex-shrink: 0; margin-top: 2px;"></i>
            <div>
                Silakan isi koordinat markas. Mainan hanya dapat melakukan absensi jika mereka berada dalam radius yang ditentukan dari titik koordinat ini.
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
            <div style="margin-bottom: 0;">
                <label for="latitude" class="toy-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="toy-input" value="{{ old('latitude', $settings['latitude']) }}" placeholder="-6.175392" required>
                @error('latitude') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
            
            <div style="margin-bottom: 0;">
                <label for="longitude" class="toy-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="toy-input" value="{{ old('longitude', $settings['longitude']) }}" placeholder="106.827153" required>
                @error('longitude') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
            <button type="button" id="btn-get-location" class="toy-btn toy-btn-small" style="background: var(--woody-blue); border-color: #1D4ED8; text-shadow: 1px 1px 0 #1D4ED8;">
                <i data-lucide="crosshair" style="width: 18px; height: 18px;"></i> Deteksi Lokasi Saat Ini
            </button>
            <a href="https://www.latlong.net/" target="_blank" class="toy-btn toy-btn-small" style="background: white; color: var(--woody-brown); border-color: #D1D5DB; text-shadow: none;">
                <i data-lucide="search" style="width: 18px; height: 18px;"></i> Cari Koordinat Online
            </a>
        </div>

        <div style="margin-bottom: 3.5rem;">
            <label for="radius" class="toy-label">Jarak Radius Maksimal (Meter)</label>
            <input type="number" name="radius" id="radius" class="toy-input" value="{{ old('radius', $settings['radius']) }}" placeholder="200" required min="1" style="max-width: 200px;">
            <span style="color: var(--text-muted); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">Rekomendasi default adalah 200 meter.</span>
            @error('radius') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
        </div>

        <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--woody-brown); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 4px dashed var(--woody-yellow); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="clock" style="width: 24px; height: 24px;"></i> Waktu Operasional Misi
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3.5rem;">
            <div style="margin-bottom: 0;">
                <label for="check_in_start" class="toy-label">Jam Buka Absen Masuk</label>
                <input type="time" name="check_in_start" id="check_in_start" class="toy-input" value="{{ old('check_in_start', $settings['check_in_start']) }}" required>
                @error('check_in_start') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
            
            <div style="margin-bottom: 0;">
                <label for="check_in_end" class="toy-label">Jam Tutup Absen Masuk</label>
                <input type="time" name="check_in_end" id="check_in_end" class="toy-input" value="{{ old('check_in_end', $settings['check_in_end']) }}" required>
                @error('check_in_end') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div style="margin-bottom: 0;">
                <label for="check_out_start" class="toy-label">Jam Buka Absen Pulang</label>
                <input type="time" name="check_out_start" id="check_out_start" class="toy-input" value="{{ old('check_out_start', $settings['check_out_start']) }}" required>
                @error('check_out_start') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 style="font-family: var(--font-heading); font-size: 1.4rem; color: var(--woody-brown); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 4px dashed var(--woody-yellow); display: flex; align-items: center; gap: 0.5rem;">
            <i data-lucide="lock" style="width: 24px; height: 24px;"></i> Keamanan
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div style="margin-bottom: 0;">
                <label for="password" class="toy-label">Ubah Password Admin</label>
                <input type="password" name="password" id="password" class="toy-input" placeholder="Isi untuk mengubah password...">
                <span style="color: var(--text-muted); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">Kosongkan jika tidak ingin mengubah.</span>
                @error('password') <span style="color: var(--woody-red); font-weight: 700; font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
            
            <div style="margin-bottom: 0;">
                <label for="password_confirmation" class="toy-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="toy-input" placeholder="Ketik ulang password baru...">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 4px dashed var(--woody-yellow);">
            <button type="submit" class="toy-btn" style="padding: 1rem 2.5rem;">
                <i data-lucide="save" style="width: 20px; height: 20px;"></i> SIMPAN PENGATURAN
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-get-location').addEventListener('click', function() {
        if (!navigator.geolocation) {
            alert('Geolocation tidak didukung oleh browser Anda.');
            return;
        }

        const button = this;
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width: 18px; height: 18px; margin-right: 0.5rem; display: inline-block; vertical-align: middle;"></i> <span style="display: inline-block; vertical-align: middle;">Mendeteksi lokasi...</span>';
        lucide.createIcons();

        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                
                button.disabled = false;
                button.innerHTML = originalText;
                lucide.createIcons();
                alert('Lokasi berhasil dideteksi!');
            },
            function(error) {
                button.disabled = false;
                button.innerHTML = originalText;
                lucide.createIcons();
                
                let message = 'Gagal mendeteksi lokasi.';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Izin akses lokasi ditolak oleh pengguna.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Informasi lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        message = 'Waktu permintaan lokasi habis.';
                        break;
                }
                alert(message);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0
            }
        );
    });
</script>
<style>
    .animate-spin { animation: spin 1s infinite linear; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection
