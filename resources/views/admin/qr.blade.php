@extends('layouts.app')

@section('title', 'Proyektor QR Code')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="font-family: var(--font-heading); font-size: 2.2rem; color: var(--woody-blue); letter-spacing: 1px; text-shadow: 2px 2px 0 white;">Proyektor QR Code</h1>
        <p style="color: var(--text-main); font-size: 1.1rem; margin-top: 0.25rem; font-weight: 700;">Tampilkan layar ini di depan markas agar mainan dapat melakukan scan absensi.</p>
    </div>
    <div style="display: flex; gap: 0.5rem;">
        <button id="btn-fullscreen" class="toy-btn toy-btn-small" style="background: white; border-color: #D1D5DB; color: var(--woody-brown); text-shadow: none;">
            <i data-lucide="maximize"></i> Layar Penuh
        </button>
    </div>
</div>

<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh;">
    <div class="toy-card" id="qr-container-card" style="width: 100%; max-width: 480px; text-align: center; border-radius: 24px; padding: 3rem 2rem; position: relative; transition: all 0.3s ease; border-width: 6px; border-color: var(--buzz-green-dark);">
        
        <!-- Animated Scanner Line -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 100%; overflow: hidden; pointer-events: none; border-radius: inherit; z-index: 10;">
            <div id="scanner-line" style="width: 100%; height: 6px; background: linear-gradient(90deg, transparent, var(--buzz-green), #A3E635, var(--buzz-green), transparent); position: absolute; top: 0; animation: scan 3s infinite ease-in-out; opacity: 0.8; box-shadow: 0 0 15px 2px var(--buzz-green);"></div>
        </div>

        <div style="margin-bottom: 1.5rem;">
            <span style="background-color: #DCFCE7; color: #166534; padding: 0.4rem 1rem; border-radius: 9999px; font-family: var(--font-heading); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 0.5rem; border: 2px solid #86EFAC;">
                <span style="width: 10px; height: 10px; background-color: var(--buzz-green); border-radius: 50%; display: inline-block; animation: pulse 1.5s infinite;"></span>
                Dynamic QR Token
            </span>
        </div>

        <!-- SVG Container -->
        <div id="qr-svg-holder" style="display: inline-flex; align-items: center; justify-content: center; background: white; padding: 1.5rem; border-radius: 16px; border: 4px solid var(--buzz-green); box-shadow: 0 8px 0 rgba(132, 204, 22, 0.2); min-height: 334px; min-width: 334px; margin-bottom: 2rem; transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
            <div style="color: var(--text-muted); font-size: 1rem; font-weight: 700;">
                <i data-lucide="loader" class="animate-spin" style="width: 42px; height: 42px; margin: 0 auto 0.5rem auto; display: block; color: var(--buzz-green);"></i>
                Membuat QR Code...
            </div>
        </div>

        <p style="color: var(--text-muted); font-size: 0.95rem; display: flex; align-items: center; justify-content: center; gap: 0.35rem; font-weight: 700;">
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
                    <div style="color: var(--woody-red); font-weight: 700;">
                        <i data-lucide="alert-triangle" style="width: 42px; height: 42px; margin:0 auto 0.5rem auto; display:block;"></i>
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
                .catch(err => {
                    // Fallback using msRequestFullscreen etc if needed, but modern browsers support standard
                    if(cardContainer.webkitRequestFullscreen) {
                        cardContainer.webkitRequestFullscreen();
                    } else if (cardContainer.msRequestFullscreen) {
                        cardContainer.msRequestFullscreen();
                    } else {
                        alert(\`Gagal mengaktifkan mode layar penuh: \${err.message}\`);
                    }
                });
            } else {
                document.exitFullscreen();
            }
        });

        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);

        function handleFullscreenChange() {
            if (document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement) {
                btnFullscreen.innerHTML = '<i data-lucide="minimize"></i> Keluar Layar Penuh';
                cardContainer.style.padding = '8rem 2rem';
                cardContainer.style.background = 'white';
                cardContainer.style.borderRadius = '0';
                cardContainer.style.maxWidth = '100%';
                cardContainer.style.border = 'none';
                document.getElementById('qr-svg-holder').style.transform = 'scale(1.75)';
                lucide.createIcons();
            } else {
                btnFullscreen.innerHTML = '<i data-lucide="maximize"></i> Layar Penuh';
                cardContainer.style.padding = '3rem 2rem';
                cardContainer.style.background = 'white';
                cardContainer.style.borderRadius = '24px';
                cardContainer.style.maxWidth = '480px';
                cardContainer.style.border = '6px solid var(--buzz-green-dark)';
                document.getElementById('qr-svg-holder').style.transform = 'scale(1)';
                lucide.createIcons();
            }
        }
    });
</script>
@endsection
