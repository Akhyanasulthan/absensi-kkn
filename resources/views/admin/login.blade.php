@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div style="display: flex; align-items: center; justify-content: center; min-height: 60vh; padding: 1rem;">
    <div class="glass-card" style="width: 100%; max-width: 420px; box-shadow: var(--shadow-lg);">
        <div style="text-align: center; margin-bottom: 2rem;">
            <div style="background-color: var(--primary-light); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem auto; color: var(--primary);">
                <i data-lucide="lock" style="width: 32px; height: 32px;"></i>
            </div>
            <h2 style="font-weight: 700; font-size: 1.5rem; color: var(--bg-sidebar);">Admin Login</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.25rem;">Masukkan kredensial Anda untuk masuk ke dashboard</p>
        </div>

        @if ($errors->any())
            <div style="background-color: var(--danger-light); color: var(--danger); border: 1px solid rgba(239, 68, 68, 0.2); padding: 1rem; border-radius: var(--radius-md); font-size: 0.875rem; margin-bottom: 1.5rem; display: flex; align-items: flex-start; gap: 0.5rem;">
                <i data-lucide="alert-circle" style="width: 18px; height: 18px; flex-shrink: 0; margin-top: 2px;"></i>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" name="email" id="email" class="form-input" placeholder="admin@kkn.com" value="{{ old('email') }}" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label for="password" class="form-label" style="margin-bottom: 0;">Password</label>
                </div>
                <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
            </div>

            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.5rem;">
                <input type="checkbox" name="remember" id="remember" style="width: 16px; height: 16px; accent-color: var(--primary);">
                <label for="remember" style="font-size: 0.875rem; color: var(--text-muted); cursor: pointer; user-select: none;">Ingat saya di perangkat ini</label>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 1rem; justify-content: center; box-shadow: var(--shadow-md);">
                Masuk <i data-lucide="arrow-right"></i>
            </button>
        </form>
    </div>
</div>
@endsection
