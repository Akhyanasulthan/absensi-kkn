@extends('layouts.app')

@section('title', 'Login')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;900&display=swap');

    body {
        /* Andy's Room Wallpaper Blue */
        background-color: #4CB1E1 !important;
        background-image: 
            radial-gradient(circle at 20% 30%, rgba(255,255,255,0.8) 10px, transparent 11px),
            radial-gradient(circle at 22% 29%, rgba(255,255,255,0.8) 15px, transparent 16px),
            radial-gradient(circle at 25% 30%, rgba(255,255,255,0.8) 12px, transparent 13px),
            radial-gradient(circle at 23% 32%, rgba(255,255,255,0.8) 14px, transparent 15px),
            
            radial-gradient(circle at 75% 60%, rgba(255,255,255,0.6) 10px, transparent 11px),
            radial-gradient(circle at 77% 59%, rgba(255,255,255,0.6) 15px, transparent 16px),
            radial-gradient(circle at 80% 60%, rgba(255,255,255,0.6) 12px, transparent 13px),
            radial-gradient(circle at 78% 62%, rgba(255,255,255,0.6) 14px, transparent 15px)
            !important;
        background-size: 200px 200px !important;
        font-family: 'Nunito', sans-serif !important;
    }
    
    .toy-card {
        background: #FDE047; /* Woody Yellow */
        border: 6px solid #EF4444; /* Woody Red */
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 15px 35px rgba(0,0,0,0.2), inset 0 0 0 6px #B91C1C, inset 0 0 0 10px #FDE047, inset 0 0 0 14px #EF4444;
        position: relative;
        overflow: hidden;
    }

    /* Cow print pattern overlay */
    .toy-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background-image: radial-gradient(#000 15%, transparent 16%), radial-gradient(#000 15%, transparent 16%);
        background-size: 60px 60px;
        background-position: 0 0, 30px 30px;
        opacity: 0.05;
        z-index: 0;
        pointer-events: none;
    }

    .toy-card > * {
        position: relative;
        z-index: 1;
    }

    .toy-title {
        font-family: 'Fredoka One', cursive;
        color: #2563EB; /* Toy Story Blue */
        font-size: 2.5rem;
        text-shadow: 2px 2px 0px #FDE047, 4px 4px 0px #EF4444;
        margin-bottom: 0.5rem;
        letter-spacing: 2px;
    }

    .toy-subtitle {
        color: #B91C1C;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .toy-input {
        background: white;
        border: 4px solid #3B82F6; /* Blue border */
        border-radius: 16px;
        padding: 1rem 1.25rem;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1E3A8A;
        width: 100%;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: inset 0 4px 6px rgba(0,0,0,0.1);
    }

    .toy-input:focus {
        outline: none;
        border-color: #8B5CF6; /* Purple */
        transform: scale(1.02);
        box-shadow: inset 0 4px 6px rgba(0,0,0,0.1), 0 0 0 4px rgba(139, 92, 246, 0.3);
    }

    .toy-label {
        font-family: 'Fredoka One', cursive;
        color: #1E3A8A;
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
        display: block;
        letter-spacing: 1px;
    }

    /* Buzz Lightyear Button */
    .toy-btn {
        background: #84CC16; /* Buzz Green */
        border: 4px solid #4D7C0F;
        border-bottom-width: 8px;
        color: white;
        font-family: 'Fredoka One', cursive;
        font-size: 1.5rem;
        padding: 1rem 2rem;
        border-radius: 99px;
        width: 100%;
        cursor: pointer;
        transition: all 0.1s ease;
        text-transform: uppercase;
        letter-spacing: 2px;
        text-shadow: 2px 2px 0px #4D7C0F;
        position: relative;
        overflow: hidden;
    }

    .toy-btn::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        border: 4px solid #A855F7; /* Buzz Purple */
        border-radius: 99px;
        opacity: 0.5;
        pointer-events: none;
    }

    .toy-btn:hover {
        background: #A3E635;
        transform: translateY(-2px);
        border-bottom-width: 10px;
        margin-top: -2px;
    }

    .toy-btn:active {
        background: #65A30D;
        transform: translateY(4px);
        border-bottom-width: 4px;
        margin-top: 4px;
    }

    .error-box {
        background: #FEF2F2;
        border: 4px dashed #EF4444;
        border-radius: 16px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        color: #B91C1C;
        font-weight: 700;
    }
</style>

<div style="display: flex; align-items: center; justify-content: center; min-height: 80vh; padding: 1rem;">
    <div class="toy-card" style="width: 100%; max-width: 450px;">
        
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <div style="background: white; width: 90px; height: 90px; border-radius: 50%; border: 6px solid #2563EB; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
                <!-- Sheriff Star Icon (Star) -->
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="#FBBF24" stroke="#D97706" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </div>
            <h2 class="toy-title">ABSENSI KKN</h2>
            <p class="toy-subtitle">Menuju tak terbatas dan melampauinya!</p>
        </div>

        @if ($errors->any())
            <div class="error-box">
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                    <i data-lucide="alert-circle" style="color: #EF4444;"></i>
                    <span style="font-size: 1.1rem; text-transform: uppercase; font-family: 'Fredoka One', cursive;">Ada Ular di Sepatuku!</span>
                </div>
                <ul style="margin: 0; padding-left: 1.5rem; font-size: 0.95rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div style="margin-bottom: 1.5rem;">
                <label for="email" class="toy-label">Email Pemain</label>
                <input type="email" name="email" id="email" class="toy-input" placeholder="andy@mainan.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div style="margin-bottom: 2rem;">
                <label for="password" class="toy-label">Kata Sandi Rahasia</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="password" class="toy-input" placeholder="••••••••" required style="padding-right: 3rem;">
                    <button type="button" onclick="togglePassword()" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); background: none; border: none; color: #3B82F6; cursor: pointer; padding: 0;">
                        <i data-lucide="eye" id="toggleIcon" style="width: 24px; height: 24px;"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="toy-btn">
                MASUK!
            </button>
        </form>
    </div>
</div>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.setAttribute('data-lucide', 'eye-off');
        } else {
            passwordInput.type = 'password';
            toggleIcon.setAttribute('data-lucide', 'eye');
        }
        
        lucide.createIcons();
    }
</script>
@endsection
