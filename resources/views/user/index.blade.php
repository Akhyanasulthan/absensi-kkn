@extends('layouts.app')

@section('title', 'Absensi Mahasiswa')

@section('content')
<style>
    /* Scoped Dark Mode & Neumorphism Overrides */
    body {
        background-color: #09090b !important; /* Zinc 950 */
        background-image: none !important;
        color: #f8fafc !important;
    }
    
    .ambient-orb {
        filter: blur(100px);
        opacity: 0.8;
    }
    .orb-1 {
        background: rgba(139, 92, 246, 0.25) !important; /* Violet */
    }
    .orb-2 {
        background: rgba(16, 185, 129, 0.2) !important; /* Emerald */
    }

    .guest-header {
        background: rgba(9, 9, 11, 0.7) !important;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important;
    }

    .guest-logo {
        color: #f8fafc !important;
    }

    .radical-card {
        background: rgba(24, 24, 27, 0.6); /* Zinc 900 */
        backdrop-filter: blur(30px);
        -webkit-backdrop-filter: blur(30px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border-radius: 32px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .user-profile-badge {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #8b5cf6, #3b82f6);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 800;
        color: white;
        box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.5);
        border: 2px solid rgba(255,255,255,0.2);
    }

    .radar-container {
        position: relative;
        width: 250px;
        height: 250px;
        margin: 2.5rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(39, 39, 42, 0.8) 0%, rgba(9, 9, 11, 0.4) 100%);
        border: 1px solid rgba(255,255,255,0.05);
        box-shadow: inset 0 0 40px rgba(0,0,0,0.5), 0 10px 30px rgba(0,0,0,0.3);
    }

    .radar-circle {
        position: absolute;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .radar-c1 { width: 100%; height: 100%; }
    .radar-c2 { width: 66%; height: 66%; border-style: dashed; }
    .radar-c3 { width: 33%; height: 33%; }

    .radar-dot {
        width: 20px;
        height: 20px;
        background: #f59e0b;
        border-radius: 50%;
        position: relative;
        z-index: 10;
        box-shadow: 0 0 20px #f59e0b;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .radar-sweep {
        position: absolute;
        width: 50%;
        height: 50%;
        top: 0;
        left: 50%;
        transform-origin: bottom left;
        background: linear-gradient(45deg, rgba(16, 185, 129, 0.8), transparent);
        border-radius: 100% 0 0 0;
        animation: radar-spin 3s linear infinite;
        opacity: 0.3;
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
        border: 2px solid #f59e0b;
        animation: pulse-ring 2s cubic-bezier(0.215, 0.61, 0.355, 1) infinite;
    }

    @keyframes pulse-ring {
        0% { width: 20px; height: 20px; opacity: 1; }
        100% { width: 150px; height: 150px; opacity: 0; }
    }

    .btn-neon {
        width: 100%;
        padding: 1.5rem;
        border-radius: 24px;
        font-size: 1.25rem;
        font-weight: 800;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        color: white;
    }

    .btn-neon::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(rgba(255,255,255,0.2), transparent);
        z-index: 1;
    }

    .btn-neon > * { position: relative; z-index: 2; }

    .btn-neon-in {
        background: linear-gradient(135deg, #10b981, #047857);
        box-shadow: 0 15px 35px -5px rgba(16, 185, 129, 0.6), inset 0 2px 5px rgba(255,255,255,0.4);
        border: 1px solid #34d399;
    }

    .btn-neon-out {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        box-shadow: 0 15px 35px -5px rgba(59, 130, 246, 0.6), inset 0 2px 5px rgba(255,255,255,0.4);
        border: 1px solid #60a5fa;
    }

    .btn-neon:hover:not(:disabled) {
        transform: translateY(-5px) scale(1.02);
        filter: brightness(1.1);
    }
    
    .btn-neon:active:not(:disabled) {
        transform: translateY(2px) scale(0.98);
    }

    .btn-neon:disabled {
        background: #3f3f46;
        box-shadow: none;
        border-color: #52525b;
        color: #a1a1aa;
        cursor: not-allowed;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .info-box {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
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
</style>

<!-- Radical Profile Card -->
<div class="radical-card fade-up" style="margin-bottom: 2rem;">
    <div class="user-profile-badge">
        <div class="avatar-circle">
            {{ substr(Auth::user()->name, 0, 1) }}
        </div>
        <div>
            <div style="font-size: 0.9rem; color: #a1a1aa; text-transform: uppercase; letter-spacing: 0.1em; font-weight: 700;">Selamat Datang</div>
            <h2 style="font-size: 1.75rem; font-weight: 800; margin: 0.25rem 0; color: white;">{{ Auth::user()->name }}</h2>
            <div style="display: inline-flex; align-items: center; gap: 0.5rem; background: rgba(255,255,255,0.1); padding: 0.25rem 0.75rem; border-radius: 99px; font-size: 0.85rem; border: 1px solid rgba(255,255,255,0.1);">
                <i data-lucide="shield-check" style="width: 14px; height: 14px; color: #34d399;"></i> {{ Auth::user()->division }}
            </div>
        </div>
    </div>
</div>

<!-- QR Alert (If Needed) -->
@if(empty($token))
    <div class="radical-card fade-up delay-1" id="scan-container" style="margin-bottom: 2rem; background: rgba(220, 38, 38, 0.15); border-color: rgba(220, 38, 38, 0.3); text-align: center;">
        <i data-lucide="alert-triangle" style="width: 48px; height: 48px; color: #f87171; margin: 0 auto 1rem auto; display: block;"></i>
        <h3 style="color: white; font-size: 1.25rem; font-weight: 700; margin-bottom: 0.5rem;">QR Code Dibutuhkan</h3>
        <p style="color: #fca5a5; font-size: 0.95rem; margin-bottom: 1.5rem;">Silakan arahkan kamera ke layar proyektor posko untuk membuka akses absensi.</p>
        <button type="button" onclick="startScanner()" class="btn-neon btn-neon-out" style="padding: 1rem; border-radius: 16px; font-size: 1rem; width: auto; display: inline-flex; flex-direction: row;">
            <i data-lucide="scan"></i> Mulai Scan
        </button>
        <div id="reader" style="width: 100%; max-width: 300px; display: none; margin: 1.5rem auto 0 auto; border-radius: 16px; overflow: hidden; background: white;"></div>
    </div>
    
    <div class="radical-card fade-up delay-1" id="scan-success-container" style="display: none; margin-bottom: 2rem; background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); align-items: center; justify-content: center; gap: 1rem;">
        <i data-lucide="check-circle" style="width: 32px; height: 32px; color: #34d399;"></i>
        <div style="color: white; font-weight: 700; font-size: 1.1rem;">Akses Terbuka</div>
    </div>
@else
    <div class="radical-card fade-up delay-1" style="margin-bottom: 2rem; background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.3); display: flex; align-items: center; justify-content: center; gap: 1rem; padding: 1rem;">
        <i data-lucide="unlock" style="width: 24px; height: 24px; color: #34d399;"></i>
        <div style="color: white; font-weight: 700; font-size: 1rem;">Sesi Aktif</div>
    </div>
@endif

<!-- Interactive GPS Radar -->
<div class="radical-card fade-up delay-2" style="text-align: center; margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h3 style="color: white; font-size: 1.25rem; font-weight: 800;">Radar Lokasi</h3>
        <button type="button" onclick="refreshLocation()" style="background: rgba(255,255,255,0.1); border: none; color: white; padding: 0.5rem; border-radius: 50%; cursor: pointer;">
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

    <div style="background: rgba(0,0,0,0.3); border-radius: 16px; padding: 1rem; margin-top: -1rem; position: relative; z-index: 20; border: 1px solid rgba(255,255,255,0.05);">
        <div id="gps-status-title" style="color: white; font-weight: 700; font-size: 1.1rem; margin-bottom: 0.25rem;">Menghubungkan Satelit...</div>
        <div id="gps-distance-text" style="color: #a1a1aa; font-size: 0.9rem;">Mencari sinyal GPS terbaik</div>
    </div>
</div>

<!-- Info & Actions -->
<div class="radical-card fade-up delay-3">
    
    <div class="info-grid">
        <div class="info-box">
            <div style="color: #a1a1aa; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Jam Masuk</div>
            <div style="color: white; font-weight: 800; font-size: 1.1rem;">{{ $settings['check_in_start'] }} - {{ $settings['check_in_end'] }}</div>
        </div>
        <div class="info-box">
            <div style="color: #a1a1aa; font-size: 0.8rem; text-transform: uppercase; font-weight: 700;">Jam Pulang</div>
            <div style="color: white; font-weight: 800; font-size: 1.1rem;">Mulai {{ $settings['check_out_start'] }}</div>
        </div>
    </div>

    <form id="attendance-form" onsubmit="event.preventDefault();">
        <input type="hidden" id="qr_data" value="{{ $token }}">
        
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <button type="button" id="btn-masuk" onclick="submitAttendance('masuk')" class="btn-neon btn-neon-in" @if(empty($token)) disabled @endif>
                <i data-lucide="fingerprint" style="width: 32px; height: 32px;"></i>
                Absen Masuk
            </button>
            <button type="button" id="btn-pulang" onclick="submitAttendance('pulang')" class="btn-neon btn-neon-out" @if(empty($token)) disabled @endif>
                <i data-lucide="log-out" style="width: 32px; height: 32px;"></i>
                Absen Pulang
            </button>
        </div>
    </form>
</div>

<!-- Modal Overlay remains mostly same but styled dark -->
<div id="feedback-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.8); backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px); z-index: 1100; display: none; align-items: center; justify-content: center; padding: 1.5rem; opacity: 0; transition: opacity 0.3s ease;">
    <div class="radical-card" style="width: 100%; max-width: 420px; text-align: center; transform: scale(0.85); transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);">
        <div id="feedback-icon-container" style="width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; font-size: 2rem;">
        </div>
        <h3 id="feedback-title" style="font-size: 1.5rem; font-weight: 800; color: white; margin-bottom: 0.75rem;">Status</h3>
        <p id="feedback-message" style="color: #a1a1aa; font-size: 1rem; line-height: 1.6; margin-bottom: 2.5rem;">Pesan</p>
        <button onclick="closeFeedbackModal()" style="width: 100%; padding: 1rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 16px; font-weight: 700; cursor: pointer;">Tutup</button>
    </div>
</div>

<style>
    #feedback-modal.show { opacity: 1 !important; }
    #feedback-modal.show .radical-card { transform: scale(1) !important; }
</style>
@endsection

@section('scripts')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    // QR Scanner
    let html5QrCode = null;
    function startScanner() {
        document.getElementById('reader').style.display = 'block';
        if (!html5QrCode) {
            html5QrCode = new Html5Qrcode("reader");
            html5QrCode.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                onScanFailure
            ).catch(err => alert("Kamera error."));
        }
    }

    function onScanSuccess(decodedText) {
        if (decodedText.includes('ref=posko_qr') || decodedText === 'posko_qr') {
            html5QrCode.stop().then(() => html5QrCode.clear());
            document.getElementById('qr_data').value = 'posko_qr';
            document.getElementById('scan-container').style.display = 'none';
            document.getElementById('scan-success-container').style.display = 'flex';
            document.getElementById('btn-masuk').disabled = false;
            document.getElementById('btn-pulang').disabled = false;
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
            dot.style.background = '#f59e0b'; dot.style.boxShadow = '0 0 20px #f59e0b';
            pulse.style.borderColor = '#f59e0b';
            sweep.style.display = 'block';
            sweep.style.background = 'linear-gradient(45deg, rgba(245, 158, 11, 0.8), transparent)';
            return;
        }

        sweep.style.display = inside ? 'block' : 'none';

        if (success) {
            if (inside) {
                dot.style.background = '#10b981'; dot.style.boxShadow = '0 0 30px #10b981';
                pulse.style.borderColor = '#10b981';
                sweep.style.background = 'linear-gradient(45deg, rgba(16, 185, 129, 0.8), transparent)';
            } else {
                dot.style.background = '#ef4444'; dot.style.boxShadow = '0 0 30px #ef4444';
                pulse.style.borderColor = '#ef4444';
            }
        } else {
            dot.style.background = '#ef4444'; dot.style.boxShadow = '0 0 20px #ef4444';
            pulse.style.borderColor = '#ef4444';
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
            icon.style.background = 'rgba(255,255,255,0.1)'; icon.style.color = 'white';
            icon.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width: 40px; height: 40px;"></i>';
        } else if (success) {
            icon.style.background = 'rgba(16, 185, 129, 0.2)'; icon.style.color = '#34d399';
            icon.innerHTML = '<i data-lucide="check" style="width: 40px; height: 40px;"></i>';
        } else {
            icon.style.background = 'rgba(239, 68, 68, 0.2)'; icon.style.color = '#f87171';
            icon.innerHTML = '<i data-lucide="x" style="width: 40px; height: 40px;"></i>';
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
