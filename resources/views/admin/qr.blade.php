@extends('layouts.app')

@section('title', 'Proyektor QR Code')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--bg-sidebar); letter-spacing: -0.02em;">Proyektor QR Code</h1>
        <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.25rem;">Tampilkan layar ini di depan posko KKN agar mahasiswa dapat melakukan scan absensi.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <button id="btn-fullscreen" class="btn btn-outline" style="font-size: 0.9rem;">
            <i data-lucide="maximize"></i> Layar Penuh (Fullscreen)
        </button>
    </div>
</div>

<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh;">
    <div class="glass-card" id="qr-container-card" style="width: 100%; max-width: 480px; text-align: center; border-radius: var(--radius-xl); box-shadow: var(--shadow-lg); padding: 3rem 2rem; position: relative; transition: all 0.3s ease;">
        
        <!-- Animated Scanner Line -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 100%; overflow: hidden; pointer-events: none; border-radius: inherit; z-index: 10;">
            <div id="scanner-line" style="width: 100%; height: 4px; background: linear-gradient(90deg, transparent, var(--primary), var(--primary-light), var(--primary), transparent); position: absolute; top: 0; animation: scan 3s infinite ease-in-out; opacity: 0.8; box-shadow: 0 0 15px 2px var(--primary);"></div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <span style="background-color: var(--primary-light); color: var(--primary); padding: 0.35rem 0.85rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 0.35rem;">
                <span style="width: 8px; height: 8px; background-color: var(--primary); border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite;"></span>
                Dynamic QR Token
            </span>
        </div>

        <!-- SVG Container -->
        <div id="qr-svg-holder" style="display: inline-flex; align-items: center; justify-content: center; background: white; padding: 1.5rem; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); min-height: 334px; min-width: 334px; margin-bottom: 2rem; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
            <div style="color: var(--text-muted); font-size: 0.95rem;">
                <i data-lucide="loader" class="animate-spin" style="width: 36px; height: 36px; margin: 0 auto 0.5rem auto; display: block;"></i>
                Membuat QR Code...
            </div>
        </div>

        <p style="color: var(--text-muted); font-size: 0.875rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem;">
            Barcode statis (permanen). Silakan cetak atau gunakan layar ini.
        </p>

    </div>
</div>

<!-- Custom Keyframe Animations -->
<style>
    @keyframes scan {
        0% { top: 0%; }
        50% { top: 100%; }
        100% { top: 0%; }
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.4); opacity: 0.5; }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-spin {
        animation: spin 1s infinite linear;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection

@section('scripts')
<script>
    // Fetch Static QR Token SVG
    function fetchQrToken() {
        const svgHolder = document.getElementById('qr-svg-holder');
        
        fetch("{{ route('admin.qr.token') }}")
            .then(response => response.json())
            .then(data => {
                svgHolder.innerHTML = data.svg;
            })
            .catch(error => {
                console.error("Gagal mengambil QR token:", error);
                svgHolder.innerHTML = `
                    <div style="color: var(--danger);">
                        <i data-lucide="alert-triangle" style="width: 32px; height: 32px; margin:0 auto 0.5rem auto; display:block;"></i>
                        Gagal memuat QR Code. Memuat ulang...
                    </div>
                `;
                lucide.createIcons();
                // retry after 3 seconds
                setTimeout(fetchQrToken, 3000);
            });
    }

    // Initialize Page
    document.addEventListener('DOMContentLoaded', () => {
        fetchQrToken();
        
        // Fullscreen Mode
        const btnFullscreen = document.getElementById('btn-fullscreen');
        const cardContainer = document.getElementById('qr-container-card');
        
        btnFullscreen.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                cardContainer.requestFullscreen()
                    .then(() => {
                        btnFullscreen.innerHTML = '<i data-lucide="minimize"></i> Tutup Layar Penuh';
                        cardContainer.style.padding = '8rem 2rem';
                        cardContainer.style.background = 'var(--bg-main)';
                        cardContainer.style.borderRadius = '0';
                        cardContainer.style.maxWidth = '100%';
                        document.getElementById('qr-svg-holder').style.transform = 'scale(1.75)';
                        lucide.createIcons();
                    })
                    .catch(err => {
                        alert(`Gagal mengaktifkan mode layar penuh: ${err.message}`);
                    });
            } else {
                document.exitFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                btnFullscreen.innerHTML = '<i data-lucide="maximize"></i> Layar Penuh (Fullscreen)';
                cardContainer.style.padding = '3rem 2rem';
                cardContainer.style.background = 'var(--card-bg)';
                cardContainer.style.borderRadius = 'var(--radius-xl)';
                cardContainer.style.maxWidth = '480px';
                document.getElementById('qr-svg-holder').style.transform = 'scale(1)';
                lucide.createIcons();
            }
        });
    });
</script>
@endsection
