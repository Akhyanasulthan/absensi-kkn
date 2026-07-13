@extends('layouts.app')

@section('title', 'Absensi Mahasiswa')

@section('content')
<div style="margin-bottom: 2.5rem; text-align: center; margin-top: 1rem;">
    <span class="badge" style="background: linear-gradient(135deg, var(--primary), var(--primary-hover)); color: white; margin-bottom: 1rem; padding: 0.5rem 1rem; font-size: 0.85rem; box-shadow: var(--shadow-glow);">
        KKN Geofence Attendance
    </span>
    <h1 style="font-size: 2.25rem; font-weight: 800; color: var(--text-main); margin-top: 0.5rem; letter-spacing: -0.04em; background: linear-gradient(135deg, var(--bg-sidebar), var(--primary)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Presensi Digital</h1>
    <p style="color: var(--text-muted); font-size: 1.05rem; margin-top: 0.5rem; font-weight: 500;">Silakan pilih nama Anda dan lakukan absensi.</p>
</div>

<!-- Barcode/Token Detection Status Widget -->
@if(empty($token))
    <div class="glass-card" style="padding: 1.25rem 1.5rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; background-color: var(--danger-light); border: 1px solid rgba(239, 68, 68, 0.2); color: #991b1b; display: flex; align-items: flex-start; gap: 1rem; box-shadow: none;">
        <i data-lucide="alert-triangle" style="width: 24px; height: 24px; flex-shrink: 0; color: var(--danger); margin-top: 2px;"></i>
        <div>
            <strong style="display: block; font-size: 1rem; margin-bottom: 0.25rem; font-weight: 700;">Barcode Tidak Terdeteksi!</strong>
            <span style="font-size: 0.9rem; opacity: 0.9;">Anda harus melakukan scan QR Code/Barcode yang ditampilkan di layar Proyektor Admin menggunakan kamera handphone Anda untuk membuka sistem absensi.</span>
        </div>
    </div>
@else
    <div class="glass-card" style="padding: 1.25rem 1.5rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; background-color: var(--success-light); border: 1px solid rgba(16, 185, 129, 0.2); color: #065f46; display: flex; align-items: center; gap: 1rem; box-shadow: none;">
        <i data-lucide="qr-code" style="width: 24px; height: 24px; color: var(--success); flex-shrink: 0;"></i>
        <div style="font-weight: 600; font-size: 0.95rem;">
            Barcode Terverifikasi. Lokasi Anda sedang dilacak.
        </div>
    </div>
@endif

<!-- GPS/Geofence Status Widget -->
<div class="glass-card" style="padding: 1.5rem; border-radius: var(--radius-xl); margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
    <div style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="position: relative; display: flex; align-items: center; justify-content: center; width: 32px; height: 32px;">
            <div id="gps-indicator" style="width: 14px; height: 14px; background-color: var(--warning); border-radius: 50%; z-index: 2; box-shadow: 0 0 10px var(--warning);"></div>
            <div id="gps-pulse" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: var(--warning); border-radius: 50%; animation: pulse-gps 2s cubic-bezier(0.165, 0.84, 0.44, 1) infinite; opacity: 0.6;"></div>
        </div>
        <div>
            <div id="gps-status-title" style="font-weight: 800; font-size: 1.05rem; color: var(--text-main); letter-spacing: -0.01em;">Mendeteksi GPS...</div>
            <div id="gps-status-desc" style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.15rem; font-weight: 500;">Izinkan akses lokasi pada browser.</div>
        </div>
    </div>
    <div style="display: flex; gap: 0.85rem; align-items: center;">
        <div id="gps-distance-badge" class="badge" style="display: none; background-color: #f1f5f9; color: var(--text-main); font-weight: 700; padding: 0.5rem 0.85rem; font-size: 0.85rem;">
            -
        </div>
        <button type="button" onclick="refreshLocation()" class="btn btn-outline" style="padding: 0.6rem; border-radius: 50%; width: 42px; height: 42px;" title="Perbarui Lokasi GPS">
            <i data-lucide="refresh-cw" style="width: 20px; height: 20px; color: var(--text-muted);"></i>
        </button>
    </div>
</div>

<!-- Main Attendance Form -->
<div class="glass-card" style="position: relative;">
    
    <!-- Disable overlay if token is missing -->
    @if(empty($token))
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(255, 255, 255, 0.7); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); z-index: 10; border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center;"></div>
    @endif

    <form id="attendance-form" onsubmit="event.preventDefault();">
        
        <!-- Hidden input to store scanned QR token -->
        <input type="hidden" id="qr_data" value="{{ $token }}">

        <div class="form-group" style="margin-bottom: 2rem;">
            <label for="user-student-select" class="form-label">Identitas Mahasiswa</label>
            <select id="user-student-select" class="form-input" onchange="onStudentSelect(this)" required style="font-size: 1.05rem; padding: 1rem 1.25rem;">
                <option value="">-- Pilih Nama Anda --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" data-name="{{ $student->name }}" data-division="{{ $student->division }}">
                        {{ $student->name }} ({{ $student->division }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="display: none;">
            <input type="hidden" id="user-name" class="form-input" required>
            <input type="hidden" id="user-division" class="form-input" required>
        </div>

        <!-- Check Time Info -->
        <div style="background-color: rgba(255,255,255,0.5); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2.5rem; font-size: 0.95rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 1rem; box-shadow: inset 0 2px 5px rgba(0,0,0,0.02);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="display: flex; align-items: center; gap: 0.6rem; font-weight: 600;"><i data-lucide="clock" style="width: 18px; height: 18px; color: var(--success);"></i> Masuk</span>
                <strong style="color: var(--text-main);">{{ $settings['check_in_start'] }} - {{ $settings['check_in_end'] }} WIB</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="display: flex; align-items: center; gap: 0.6rem; font-weight: 600;"><i data-lucide="clock" style="width: 18px; height: 18px; color: var(--primary);"></i> Pulang</span>
                <strong style="color: var(--text-main);">Mulai {{ $settings['check_out_start'] }} WIB</strong>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed var(--border-color); padding-top: 1rem; margin-top: 0.25rem;">
                <span style="display: flex; align-items: center; gap: 0.6rem; font-weight: 600;"><i data-lucide="map-pin" style="width: 18px; height: 18px; color: var(--warning);"></i> Radius Posko</span>
                <strong style="color: var(--bg-sidebar);">{{ $settings['radius'] }} meter</strong>
            </div>
        </div>

        <!-- Submit Triggers -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
            <button type="button" id="btn-masuk" onclick="submitAttendance('masuk')" class="btn btn-success" @if(empty($token)) disabled @endif style="padding: 1.5rem 0.5rem; flex-direction: column; gap: 0.5rem; border-radius: var(--radius-lg);">
                <span style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; letter-spacing: -0.01em;"><i data-lucide="log-in" style="width: 24px; height: 24px;"></i> Absen Masuk</span>
                <span style="font-size: 0.8rem; font-weight: 500; opacity: 0.9; letter-spacing: 0.03em;">{{ $settings['check_in_start'] }} - {{ $settings['check_in_end'] }}</span>
            </button>
            <button type="button" id="btn-pulang" onclick="submitAttendance('pulang')" class="btn btn-primary" @if(empty($token)) disabled @endif style="padding: 1.5rem 0.5rem; flex-direction: column; gap: 0.5rem; border-radius: var(--radius-lg);">
                <span style="display: flex; align-items: center; gap: 0.5rem; font-size: 1.15rem; letter-spacing: -0.01em;"><i data-lucide="log-out" style="width: 24px; height: 24px;"></i> Absen Pulang</span>
                <span style="font-size: 0.8rem; font-weight: 500; opacity: 0.9; letter-spacing: 0.03em;">Mulai {{ $settings['check_out_start'] }}</span>
            </button>
        </div>
    </form>
</div>

<!-- Success / Error Animated Feedback Modal -->
<div id="feedback-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 1100; display: none; align-items: center; justify-content: center; padding: 1.5rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="glass-card" style="width: 100%; max-width: 420px; text-align: center; background: white; padding: 3.5rem 2.5rem; transform: scale(0.85); transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); border-radius: var(--radius-xl);">
        
        <!-- Feedback Icon Holder -->
        <div id="feedback-icon-container" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; font-size: 2rem;">
            <!-- Dynamic icon inserted by JS -->
        </div>

        <h3 id="feedback-title" style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.75rem; letter-spacing: -0.02em;">Presensi Sukses</h3>
        <p id="feedback-message" style="color: var(--text-muted); font-size: 1rem; line-height: 1.6; margin-bottom: 2.5rem;">
            Presensi Anda berhasil masuk.
        </p>

        <button onclick="closeFeedbackModal()" class="btn btn-outline" style="width: 100%; justify-content: center; padding: 1rem;">
            Tutup
        </button>
    </div>
</div>

<style>
    @keyframes pulse-gps {
        0% { transform: scale(0.8); opacity: 0.8; }
        50% { opacity: 0.4; }
        100% { transform: scale(3); opacity: 0; }
    }
    .animate-spin {
        animation: spin 1s infinite linear;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Modal show animation classes */
    #feedback-modal.show {
        opacity: 1 !important;
    }
    #feedback-modal.show .glass-card {
        transform: scale(1) !important;
    }
</style>
@endsection

@section('scripts')
<script>
    // KKN Location Configuration passed from controller
    const poskoConfig = {
        lat: {{ $settings['latitude'] }},
        lng: {{ $settings['longitude'] }},
        radius: {{ $settings['radius'] }}
    };

    // User Location state
    let userLocation = {
        lat: null,
        lng: null,
        distance: null,
        inside: false,
        ready: false
    };
    
    let watchId = null;

    // Load saved Name & Division
    document.addEventListener('DOMContentLoaded', () => {
        const studentSelect = document.getElementById('user-student-select');
        if (studentSelect) {
            const savedStudentId = localStorage.getItem('kkn_student_id');
            if (savedStudentId) {
                studentSelect.value = savedStudentId;
                onStudentSelect(studentSelect);
            }
        }
        startTrackingLocation();
    });

    // Populate division and hidden name field
    function onStudentSelect(selectEl) {
        const nameInput = document.getElementById('user-name');
        const divisionInput = document.getElementById('user-division');
        
        if (selectEl && selectEl.selectedIndex > 0) {
            const selectedOption = selectEl.options[selectEl.selectedIndex];
            const name = selectedOption.getAttribute('data-name');
            const division = selectedOption.getAttribute('data-division');
            
            nameInput.value = name;
            divisionInput.value = division;
            
            localStorage.setItem('kkn_student_id', selectEl.value);
            localStorage.setItem('kkn_student_name', name);
            localStorage.setItem('kkn_student_division', division);
        } else {
            if (nameInput) nameInput.value = '';
            if (divisionInput) divisionInput.value = '';
            localStorage.removeItem('kkn_student_id');
            localStorage.removeItem('kkn_student_name');
            localStorage.removeItem('kkn_student_division');
        }
    }

    // Haversine distance formula in JS
    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth radius in meters
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
            Math.sin(dLon/2) * Math.sin(dLon/2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c; // meters
    }

    // Continuous browser tracking
    function startTrackingLocation() {
        if (!navigator.geolocation) {
            updateGpsWidget(false, "GPS Tidak Didukung", "Browser Anda tidak mendukung deteksi lokasi.");
            return;
        }

        const gpsOptions = {
            enableHighAccuracy: true,
            timeout: 15000,
            maximumAge: 0
        };

        // Watch location
        watchId = navigator.geolocation.watchPosition(
            (position) => {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const dist = getDistance(lat, lng, poskoConfig.lat, poskoConfig.lng);
                const inside = dist <= poskoConfig.radius;

                userLocation = {
                    lat: lat,
                    lng: lng,
                    distance: dist,
                    inside: inside,
                    ready: true
                };

                updateGpsWidget(true, 
                    inside ? "Lokasi Siap (Di Dalam Radius)" : "Lokasi Siap (Di Luar Radius)",
                    inside ? `Jarak Anda: ${dist.toFixed(1)} meter dari posko KKN.` : `Jarak Anda: ${dist.toFixed(1)} meter dari posko. Maksimal: ${poskoConfig.radius}m.`,
                    inside,
                    dist
                );
            },
            (error) => {
                console.error("Kesalahan lokasi:", error);
                let message = "Akses GPS Gagal";
                let desc = "Gagal membaca lokasi. Pastikan GPS aktif dan berikan izin.";
                if (error.code === error.PERMISSION_DENIED) {
                    desc = "Izin lokasi diblokir. Harap aktifkan izin lokasi di pengaturan browser Anda.";
                }
                userLocation.ready = false;
                updateGpsWidget(false, message, desc);
            },
            gpsOptions
        );
    }

    // Manual refresh for user location
    function refreshLocation() {
        if (!navigator.geolocation) return;
        
        // Stop current tracking
        if (watchId !== null) {
            navigator.geolocation.clearWatch(watchId);
            watchId = null;
        }
        
        // UI Feedback
        const btnIcon = document.querySelector('button[onclick="refreshLocation()"] i');
        if (btnIcon) btnIcon.classList.add('animate-spin');
        updateGpsWidget(true, "Memperbarui Lokasi...", "Sedang mencari sinyal GPS terbaru...", false, 0);
        document.getElementById('gps-distance-badge').style.display = 'none';

        // Restart tracking after brief delay for visual feedback
        setTimeout(() => {
            startTrackingLocation();
            if (btnIcon) btnIcon.classList.remove('animate-spin');
        }, 800);
    }

    function updateGpsWidget(success, title, desc, inside = false, distance = null) {
        const indicator = document.getElementById('gps-indicator');
        const titleEl = document.getElementById('gps-status-title');
        const descEl = document.getElementById('gps-status-desc');
        const badge = document.getElementById('gps-distance-badge');

        titleEl.innerText = title;
        descEl.innerText = desc;

        if (success) {
            if (inside) {
                indicator.style.backgroundColor = 'var(--success)';
                indicator.style.boxShadow = '0 0 12px var(--success)';
                badge.style.display = 'block';
                badge.style.backgroundColor = 'var(--success-light)';
                badge.style.color = 'var(--success)';
                badge.style.borderColor = 'rgba(16, 185, 129, 0.2)';
                badge.innerText = `${distance.toFixed(0)}m`;
            } else {
                indicator.style.backgroundColor = 'var(--danger)';
                indicator.style.boxShadow = '0 0 12px var(--danger)';
                badge.style.display = 'block';
                badge.style.backgroundColor = 'var(--danger-light)';
                badge.style.color = 'var(--danger)';
                badge.style.borderColor = 'rgba(239, 68, 68, 0.2)';
                badge.innerText = `${distance.toFixed(0)}m`;
            }
        } else {
            indicator.style.backgroundColor = 'var(--danger)';
            indicator.style.boxShadow = '0 0 12px var(--danger)';
        }
    }

    // Submit Attendance directly using url token
    function submitAttendance(action) {
        const name = document.getElementById('user-name').value.trim();
        const division = document.getElementById('user-division').value.trim();
        const qrData = document.getElementById('qr_data').value;

        // 1. Form Validation
        if (!name || !division) {
            alert("Harap isi Nama Lengkap dan Divisi KKN terlebih dahulu.");
            return;
        }

        // 2. Barcode Token check
        if (!qrData) {
            alert("Barcode/QR Code tidak terdeteksi. Silakan scan ulang QR Code di proyektor admin.");
            return;
        }

        // 3. Location Check
        if (!userLocation.ready) {
            alert("Lokasi GPS Anda belum siap. Pastikan izin lokasi aktif dan tunggu beberapa saat.");
            return;
        }

        if (!userLocation.inside) {
            alert(`Anda berada di luar jangkauan posko KKN! Jarak Anda: ${userLocation.distance.toFixed(1)} meter (Maksimal radius: ${poskoConfig.radius}m). Silakan dekati posko.`);
            return;
        }

        // Show loading state
        showFeedbackModal(true, "Memproses Presensi...", "Mengirim data presensi ke server. Harap tunggu.", true);

        // POST request to backend
        fetch("/attendance", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                name: name,
                division: division,
                latitude: userLocation.lat,
                longitude: userLocation.lng,
                action: action,
                qr_data: qrData
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            if (res.status === 200) {
                // Success
                showFeedbackModal(false, `Absen ${action === 'masuk' ? 'Masuk' : 'Pulang'} Sukses!`, res.body.message, false, true);
            } else {
                // Error
                showFeedbackModal(false, "Absensi Gagal", res.body.message || "Terjadi kesalahan pada server.", false, false);
            }
        })
        .catch(err => {
            console.error("Error submit attendance:", err);
            showFeedbackModal(false, "Kesalahan Jaringan", "Gagal menghubungi server. Periksa koneksi internet Anda.", false, false);
        });
    }

    function showFeedbackModal(isLoading, title, message, spinner = false, isSuccess = true) {
        const modal = document.getElementById('feedback-modal');
        modal.style.display = 'flex';
        // Small delay to allow display block to render before applying opacity class for transition
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        document.getElementById('feedback-title').innerText = title;
        document.getElementById('feedback-message').innerText = message;

        const iconContainer = document.getElementById('feedback-icon-container');
        iconContainer.innerHTML = '';

        if (spinner) {
            iconContainer.style.backgroundColor = 'var(--primary-soft)';
            iconContainer.style.color = 'var(--primary)';
            iconContainer.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width: 38px; height: 38px;"></i>';
        } else if (isSuccess) {
            iconContainer.style.backgroundColor = 'var(--success-light)';
            iconContainer.style.color = 'var(--success)';
            iconContainer.innerHTML = '<i data-lucide="check" style="width: 38px; height: 38px; stroke-width: 3px;"></i>';
        } else {
            iconContainer.style.backgroundColor = 'var(--danger-light)';
            iconContainer.style.color = 'var(--danger)';
            iconContainer.innerHTML = '<i data-lucide="x" style="width: 38px; height: 38px; stroke-width: 3px;"></i>';
        }
        lucide.createIcons();
    }

    function closeFeedbackModal() {
        const modal = document.getElementById('feedback-modal');
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300); // Wait for transition to finish
    }
</script>
@endsection
