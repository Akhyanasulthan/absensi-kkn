@extends('layouts.app')

@section('title', 'Absensi Mahasiswa')

@section('content')
<style>
    /* Scoped Toy Story Overrides for Scanner */
    
    .etch-a-sketch {
        background: #EF4444; /* Bright Red */
        border: 4px solid #B91C1C; /* Dark Red border */
        border-radius: 24px;
        padding: 1.5rem 1.5rem 3rem 1.5rem; /* Extra padding at bottom for knobs */
        position: relative;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2), inset 0 6px 0 rgba(255,255,255,0.2);
        margin-bottom: 2rem;
    }

    .etch-screen {
        background: #E5E7EB; /* Light gray screen */
        border: 8px solid #9CA3AF;
        border-radius: 12px;
        padding: 1.5rem;
        min-height: 250px;
        box-shadow: inset 0 4px 10px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    /* Etch A Sketch Knobs */
    .etch-knob {
        position: absolute;
        bottom: 15px;
        width: 40px;
        height: 40px;
        background: white;
        border-radius: 50%;
        box-shadow: 0 4px 0 #D1D5DB, 0 8px 10px rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .etch-knob::after {
        content: '';
        width: 30px; height: 30px;
        border-radius: 50%;
        border: 2px solid #E5E7EB;
    }
    .knob-left { left: 20px; }
    .knob-right { right: 20px; }

    /* Woody Profile Card */
    .sheriff-badge-container {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .sheriff-badge {
        width: 70px;
        height: 70px;
        background: #FBBF24;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-family: var(--font-heading);
        color: #B45309;
        border: 4px dashed #D97706;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .info-box {
        background: #EFF6FF;
        border: 3px solid #60A5FA;
        border-radius: 16px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        box-shadow: 0 4px 0 #93C5FD;
    }

    .fade-up {
        animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(30px);
    }
    
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }

    @keyframes fadeUp {
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Radar overrides for toy theme */
    .radar-container {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 1.5rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #D1FAE5; /* Light green */
        border: 4px solid #10B981;
        box-shadow: inset 0 0 20px rgba(16, 185, 129, 0.2);
    }

    .radar-circle {
        position: absolute;
        border-radius: 50%;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .radar-c1 { width: 100%; height: 100%; }
    .radar-c2 { width: 66%; height: 66%; border-style: dashed; }
    .radar-c3 { width: 33%; height: 33%; }

    .radar-dot {
        width: 20px;
        height: 20px;
        background: #F59E0B;
        border-radius: 50%;
        position: relative;
        z-index: 10;
        border: 2px solid white;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .radar-sweep {
        position: absolute;
        width: 50%;
        height: 50%;
        top: 0;
        left: 50%;
        transform-origin: bottom left;
        background: linear-gradient(45deg, rgba(16, 185, 129, 0.5), transparent);
        border-radius: 100% 0 0 0;
        animation: radar-spin 3s linear infinite;
        display: none;
    }

    @keyframes radar-spin {
        to { transform: rotate(360deg); }
    }

    .radar-pulse {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        width: 20px; height: 20px;
        border-radius: 50%;
        border: 3px solid #F59E0B;
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }

    @keyframes pulse-ring {
        0% { width: 20px; height: 20px; opacity: 1; }
        100% { width: 120px; height: 120px; opacity: 0; }
    }
</style>

<!-- Woody Profile Card -->
<div class="toy-card fade-up" style="margin-bottom: 2rem;">
    <div class="sheriff-badge-container">
        <div class="sheriff-badge">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div>
            <div style="font-family: var(--font-heading); color: var(--woody-red); font-size: 1.2rem; margin-bottom: -0.25rem;">Howdy, Partner!</div>
            <h2 style="font-family: var(--font-heading); font-size: 1.8rem; margin: 0; color: var(--woody-blue);">{{ Auth::user()->name }}</h2>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: #DBEAFE; padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.85rem; border: 2px solid #93C5FD; font-weight: 700; color: #1E3A8A; margin-top: 0.25rem;">
                <i data-lucide="star" style="width: 14px; height: 14px; fill: #FBBF24; color: #FBBF24;"></i> {{ Auth::user()->division }}
            </div>
        </div>
    </div>
</div>

<!-- QR Alert (Etch A Sketch) -->
<div class="etch-a-sketch fade-up delay-1">
    <div class="etch-knob knob-left"></div>
    <div class="etch-knob knob-right"></div>
    
    <div class="etch-screen" id="main-screen">
        @if(empty($token))
            <div id="scan-container">
                <i data-lucide="scan" style="width: 48px; height: 48px; color: #4B5563; margin: 0 auto 1rem auto; display: block;"></i>
                <h3 style="color: #1F2937; font-family: var(--font-heading); font-size: 1.4rem; margin-bottom: 0.5rem;">Cari QR Code!</h3>
                <p style="color: #4B5563; font-size: 0.95rem; margin-bottom: 1.5rem; font-weight: 700;">Arahkan kamera ke layar proyektor posko.</p>
                <button type="button" onclick="startScanner()" class="toy-btn" style="width: auto;">
                    Mulai Scan!
                </button>
            </div>
            <div id="reader" style="width: 100%; max-width: 250px; display: none; border-radius: 12px; overflow: hidden; border: 4px solid #4B5563;"></div>
            
            <div id="scan-success-container" style="display: none; align-items: center; justify-content: center; flex-direction: column; gap: 0.5rem;">
                <i data-lucide="check-circle" style="width: 64px; height: 64px; color: #10B981;"></i>
                <div style="color: #1F2937; font-weight: 700; font-size: 1.2rem; font-family: var(--font-heading);">Akses Terbuka!</div>
            </div>
        @else
            <div style="display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 0.5rem;">
                <i data-lucide="unlock" style="width: 64px; height: 64px; color: #10B981;"></i>
                <div style="color: #1F2937; font-weight: 700; font-size: 1.2rem; font-family: var(--font-heading);">Sesi Aktif</div>
            </div>
        @endif
    </div>
</div>

<!-- Interactive GPS Radar (Toy Style) -->
<div class="toy-card fade-up delay-2" style="text-align: center; margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="color: var(--woody-blue); font-size: 1.25rem; font-family: var(--font-heading);">Radar Star Command</h3>
        <button type="button" onclick="refreshLocation()" style="background: white; border: 3px solid #E5E7EB; color: #6B7280; padding: 0.5rem; border-radius: 50%; cursor: pointer; box-shadow: 0 2px 0 #E5E7EB;">
            <i data-lucide="refresh-cw" style="width: 18px; height: 18px;" class="icon-spin-target"></i>
        </button>
    </div>

    <div class="radar-container">
        <div class="radar-circle radar-c1"></div>
        <div class="radar-circle radar-c2"></div>
        <div class="radar-circle radar-c3"></div>
        
        <div class="radar-sweep" id="radar-sweep"></div>
        
        <div class="radar-dot" id="radar-dot">
            <div class="radar-pulse" id="radar-pulse"></div>
        </div>
    </div>

    <div style="background: #F3F4F6; border-radius: 12px; padding: 1rem; border: 3px dashed #D1D5DB;">
        <div id="gps-status-title" style="color: var(--text-main); font-family: var(--font-heading); font-size: 1.1rem; margin-bottom: 0.25rem;">Menghubungkan Satelit...</div>
        <div id="gps-distance-text" style="color: #4B5563; font-size: 0.9rem; font-weight: 700;">Mencari sinyal GPS terbaik</div>
    </div>
</div>

<!-- Info & Actions -->
<div class="toy-card fade-up delay-3">
    
    <div class="info-grid">
        <div class="info-box">
            <div style="color: #3B82F6; font-size: 0.8rem; font-family: var(--font-heading);">JAM MASUK</div>
            <div style="color: #1E3A8A; font-weight: 800; font-size: 1.1rem;">{{ $settings['check_in_start'] }} - {{ $settings['check_in_end'] }}</div>
        </div>
        <div class="info-box">
            <div style="color: #3B82F6; font-size: 0.8rem; font-family: var(--font-heading);">JAM PULANG</div>
            <div style="color: #1E3A8A; font-weight: 800; font-size: 1.1rem;">Mulai {{ $settings['check_out_start'] }}</div>
        </div>
    </div>

    <form id="attendance-form" onsubmit="event.preventDefault();">
        <input type="hidden" id="qr_data" value="{{ $token }}">
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <button type="button" id="btn-masuk" onclick="submitAttendance('masuk')" class="toy-btn" @if(empty($token)) disabled style="opacity: 0.5;" @endif>
                <i data-lucide="fingerprint" style="width: 24px; height: 24px;"></i>
                ABSEN MASUK
            </button>
            <button type="button" id="btn-pulang" onclick="submitAttendance('pulang')" class="toy-btn" style="background: var(--woody-blue); border-color: #1D4ED8; text-shadow: 1px 1px 0 #1D4ED8;" @if(empty($token)) disabled style="opacity: 0.5;" @endif>
                <i data-lucide="log-out" style="width: 24px; height: 24px;"></i>
                ABSEN PULANG
            </button>
        </div>
    </form>
</div>

<!-- Modal Overlay -->
<div id="feedback-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.6); backdrop-filter: blur(5px); -webkit-backdrop-filter: blur(5px); z-index: 1100; display: none; align-items: center; justify-content: center; padding: 1.5rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="toy-card" style="width: 100%; max-width: 420px; text-align: center; transform: scale(0.85); transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); border-width: 6px;">
        <div id="feedback-icon-container" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; font-size: 2rem; border: 4px solid transparent;">
        </div>
        <h3 id="feedback-title" style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--woody-blue); margin-bottom: 0.75rem;">Status</h3>
        <p id="feedback-message" style="color: var(--text-muted); font-size: 1rem; font-weight: 700; margin-bottom: 2.5rem;">Pesan</p>
        <button onclick="closeFeedbackModal()" class="toy-btn" style="width: 100%; background: #9CA3AF; border-color: #4B5563; text-shadow: none;">TUTUP</button>
    </div>
</div>

<style>
    #feedback-modal.show { opacity: 1 !important; }
    #feedback-modal.show .toy-card { transform: scale(1) !important; }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // QR Scanner
    let html5QrCode = null;
    function startScanner() {
        document.getElementById('scan-container').style.display = 'none';
        document.getElementById('reader').style.display = 'block';
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                alert("Kamera error.");
                document.getElementById('scan-container').style.display = 'block';
                document.getElementById('reader').style.display = 'none';
            });
        }
    }

    function onScanSuccess(decodedText) {
        if (decodedText.includes('ref=posko_qr') || decodedText === 'posko_qr') {
            html5QrCode.stop().then(() => html5QrCode.clear());
            document.getElementById('qr_data').value = 'posko_qr';
            document.getElementById('reader').style.display = 'none';
            document.getElementById('scan-success-container').style.display = 'flex';
            
            const btnMasuk = document.getElementById('btn-masuk');
            const btnPulang = document.getElementById('btn-pulang');
            btnMasuk.disabled = false; btnMasuk.style.opacity = '1';
            btnPulang.disabled = false; btnPulang.style.opacity = '1';
        } else {
            alert('QR Code tidak valid!');
        }
    }
    function onScanFailure() {}

    // GPS Logic
    const poskoConfig = { lat: {{ $settings['latitude'] }}, lng: {{ $settings['longitude'] }}, radius: {{ $settings['radius'] }} };
    let userLocation = { ready: false };
    let watchId = null;

    document.addEventListener('DOMContentLoaded', startTrackingLocation);

    function getDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon/2) * Math.sin(dLon/2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    }

    function startTrackingLocation() {
        if (!navigator.geolocation) { updateRadar(false, "Tidak Didukung", "Browser menolak GPS"); return; }
        watchId = navigator.geolocation.watchPosition(
            (pos) => {
                const dist = getDistance(pos.coords.latitude, pos.coords.longitude, poskoConfig.lat, poskoConfig.lng);
                const inside = dist <= poskoConfig.radius;
                userLocation = { lat: pos.coords.latitude, lng: pos.coords.longitude, distance: dist, inside: inside, ready: true };
                updateRadar(true, inside ? "Target Terkunci" : "Di Luar Jangkauan", `Jarak: ${dist.toFixed(1)}m / ${poskoConfig.radius}m`, inside);
            },
            (err) => { userLocation.ready = false; updateRadar(false, "Sinyal Hilang", "Aktifkan GPS Anda"); },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    }

    function refreshLocation() {
        if (!navigator.geolocation) return;
        if (watchId !== null) { navigator.geolocation.clearWatch(watchId); watchId = null; }
        const btn = document.querySelector('.icon-spin-target');
        btn.classList.add('animate-spin');
        updateRadar(true, "Memindai...", "Mencari kordinat presisi", false, true);
        setTimeout(() => { startTrackingLocation(); btn.classList.remove('animate-spin'); }, 1000);
    }

    function updateRadar(success, title, desc, inside = false, scanning = false) {
        document.getElementById('gps-status-title').innerText = title;
        document.getElementById('gps-distance-text').innerText = desc;
        
        const dot = document.getElementById('radar-dot');
        const pulse = document.getElementById('radar-pulse');
        const sweep = document.getElementById('radar-sweep');

        if (scanning) {
            dot.style.background = '#F59E0B'; dot.style.borderColor = 'white';
            pulse.style.borderColor = '#F59E0B';
            sweep.style.display = 'block';
            sweep.style.background = 'linear-gradient(45deg, rgba(245, 158, 11, 0.5), transparent)';
            return;
        }

        sweep.style.display = inside ? 'block' : 'none';

        if (success) {
            if (inside) {
                dot.style.background = '#10B981'; dot.style.borderColor = 'white';
                pulse.style.borderColor = '#10B981';
                sweep.style.background = 'linear-gradient(45deg, rgba(16, 185, 129, 0.5), transparent)';
            } else {
                dot.style.background = '#EF4444'; dot.style.borderColor = 'white';
                pulse.style.borderColor = '#EF4444';
            }
        } else {
            dot.style.background = '#EF4444'; dot.style.borderColor = 'white';
            pulse.style.borderColor = '#EF4444';
        }
    }

    function submitAttendance(action) {
        if (!document.getElementById('qr_data').value) { alert("QR Code dibutuhkan."); return; }
        if (!userLocation.ready) { alert("Tunggu GPS mengunci lokasi."); return; }
        if (!userLocation.inside) { alert(`Jarak Anda ${userLocation.distance.toFixed(1)}m. Harus dalam radius ${poskoConfig.radius}m.`); return; }

        showModal(true, "Memproses...", "Otentikasi lokasi", true);
        
        fetch("/attendance", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify({ latitude: userLocation.lat, longitude: userLocation.lng, action: action, qr_data: document.getElementById('qr_data').value })
        }).then(r => r.json().then(d => ({s: r.status, b: d}))).then(res => {
            if (res.s === 200) showModal(false, "Berhasil!", res.b.message, false, true);
            else showModal(false, "Gagal", res.b.message || "Error", false, false);
        }).catch(err => showModal(false, "Error", "Jaringan terputus.", false, false));
    }

    function showModal(loading, title, message, spinner, success=true) {
        const modal = document.getElementById('feedback-modal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
        document.getElementById('feedback-title').innerText = title;
        document.getElementById('feedback-message').innerText = message;
        
        const icon = document.getElementById('feedback-icon-container');
        if (spinner) {
            icon.style.background = '#E5E7EB'; icon.style.color = '#4B5563'; icon.style.borderColor = '#D1D5DB';
            icon.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width: 40px; height: 40px;"></i>';
        } else if (success) {
            icon.style.background = '#D1FAE5'; icon.style.color = '#10B981'; icon.style.borderColor = '#34D399';
            icon.innerHTML = '<i data-lucide="check" style="width: 40px; height: 40px;"></i>';
        } else {
            icon.style.background = '#FEE2E2'; icon.style.color = '#EF4444'; icon.style.borderColor = '#FCA5A5';
            icon.innerHTML = '<i data-lucide="alert-triangle" style="width: 40px; height: 40px;"></i>';
        }
        lucide.createIcons();
    }
    function closeFeedbackModal() {
        const modal = document.getElementById('feedback-modal');
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
</script>
<style>
    .animate-spin { animation: spin 1s infinite linear; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection
