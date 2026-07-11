@extends('layouts.app')

@section('title', 'Pengaturan Posko')

@section('content')
<div style="margin-bottom: 2.5rem;">
    <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--text-main); letter-spacing: -0.04em;">Pengaturan Posko KKN</h1>
    <p style="color: var(--text-muted); font-size: 1rem; margin-top: 0.5rem;">Kelola lokasi geofence posko, jarak radius, serta jam operasional absensi.</p>
</div>

@if (session('success'))
    <div style="background-color: var(--success-light); color: var(--success-hover); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1.25rem; border-radius: var(--radius-md); font-size: 0.95rem; font-weight: 500; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: var(--shadow-sm);">
        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

<div class="glass-card" style="max-width: 840px; padding: 2.5rem;">
    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        
        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.01em;">
            <i data-lucide="info" style="color: var(--primary); width: 20px; height: 20px;"></i> Informasi Umum
        </h3>

        <div class="form-group" style="margin-bottom: 2.5rem;">
            <label for="kkn_name" class="form-label">Nama Posko / Identitas KKN</label>
            <input type="text" name="kkn_name" id="kkn_name" class="form-input" value="{{ old('kkn_name', $settings['kkn_name']) }}" placeholder="Contoh: KKN Posko Desa Sukamaju" required>
            @error('kkn_name') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
        </div>

        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.01em;">
            <i data-lucide="map-pin" style="color: var(--primary); width: 20px; height: 20px;"></i> Koordinat Geofence & Radius
        </h3>

        <div style="background-color: var(--primary-light); color: var(--primary-hover); padding: 1.25rem; border-radius: var(--radius-md); font-size: 0.95rem; font-weight: 500; margin-bottom: 2rem; display: flex; align-items: flex-start; gap: 0.75rem; line-height: 1.5;">
            <i data-lucide="info" style="width: 20px; height: 20px; flex-shrink: 0; margin-top: 2px;"></i>
            <div>
                Silakan isi koordinat posko KKN. Mahasiswa hanya dapat melakukan absensi jika mereka berada dalam radius yang ditentukan dari titik koordinat ini.
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.25rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" name="latitude" id="latitude" class="form-input" value="{{ old('latitude', $settings['latitude']) }}" placeholder="-6.175392" required>
                @error('latitude') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" name="longitude" id="longitude" class="form-input" value="{{ old('longitude', $settings['longitude']) }}" placeholder="106.827153" required>
                @error('longitude') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2.5rem;">
            <button type="button" id="btn-get-location" class="btn btn-outline" style="font-size: 0.9rem;">
                <i data-lucide="crosshair" style="width: 18px; height: 18px;"></i> Deteksi Lokasi Saya Saat Ini
            </button>
            <a href="https://www.latlong.net/" target="_blank" class="btn btn-outline" style="font-size: 0.9rem; color: var(--text-muted);">
                <i data-lucide="search" style="width: 18px; height: 18px;"></i> Cari Koordinat Online
            </a>
        </div>

        <div class="form-group" style="margin-bottom: 3.5rem;">
            <label for="radius" class="form-label">Jarak Radius Maksimal (Meter)</label>
            <input type="number" name="radius" id="radius" class="form-input" value="{{ old('radius', $settings['radius']) }}" placeholder="200" required min="1" style="max-width: 200px;">
            <span style="color: var(--text-muted); font-size: 0.85rem; display: block; margin-top: 0.5rem;">Rekomendasi default adalah 200 meter.</span>
            @error('radius') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
        </div>

        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.01em;">
            <i data-lucide="clock" style="color: var(--primary); width: 20px; height: 20px;"></i> Waktu Operasional Absensi
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3.5rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="check_in_start" class="form-label">Jam Buka Absen Masuk</label>
                <input type="time" name="check_in_start" id="check_in_start" class="form-input" value="{{ old('check_in_start', $settings['check_in_start']) }}" required>
                @error('check_in_start') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="check_in_end" class="form-label">Jam Tutup Absen Masuk</label>
                <input type="time" name="check_in_end" id="check_in_end" class="form-input" value="{{ old('check_in_end', $settings['check_in_end']) }}" required>
                @error('check_in_end') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="check_out_start" class="form-label">Jam Buka Absen Pulang</label>
                <input type="time" name="check_out_start" id="check_out_start" class="form-input" value="{{ old('check_out_start', $settings['check_out_start']) }}" required>
                @error('check_out_start') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
        </div>

        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 0.5rem; letter-spacing: -0.01em;">
            <i data-lucide="lock" style="color: var(--primary); width: 20px; height: 20px;"></i> Keamanan
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="password" class="form-label">Ubah Password Admin</label>
                <input type="password" name="password" id="password" class="form-input" placeholder="Isi untuk mengubah password...">
                <span style="color: var(--text-muted); font-size: 0.85rem; display: block; margin-top: 0.5rem;">Kosongkan jika tidak ingin mengubah.</span>
                @error('password') <span style="color: var(--danger); font-size: 0.85rem; display: block; margin-top: 0.5rem;">{{ $message }}</span> @enderror
            </div>
            
            <div class="form-group" style="margin-bottom: 0;">
                <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Ketik ulang password baru...">
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; padding-top: 1.5rem; border-top: 1px solid var(--border-color);">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1rem;">
                <i data-lucide="save" style="width: 18px; height: 18px;"></i> Simpan Pengaturan
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
        button.innerHTML = '<i data-lucide="loader" class="animate-spin"></i> Mendeteksi lokasi...';
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
@endsection
