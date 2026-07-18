<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{ \App\Models\Setting::getValue('kkn_name', 'KKN Posko') }}</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom CSS Design System - Premium Modern -->
    <style>
        :root {
            /* Premium Color Palette - Updated Neon/Glass Style */
            --primary: #6366f1; /* Indigo 500 */
            --primary-hover: #4f46e5; /* Indigo 600 */
            --primary-light: #e0e7ff; 
            --primary-soft: rgba(99, 102, 241, 0.12);
            
            --success: #10b981; /* Emerald 500 */
            --success-hover: #059669; /* Emerald 600 */
            --success-light: #d1fae5;
            
            --warning: #f59e0b; /* Amber 500 */
            --warning-light: #fef3c7;
            
            --danger: #ef4444; /* Red 500 */
            --danger-light: #fee2e2;
            
            /* Backgrounds & Surfaces */
            --bg-main: #f8fafc; /* Lighter cool grey */
            --bg-sidebar: #0f172a; /* Slate 900 */
            --bg-sidebar-hover: #1e293b;
            
            /* Text Colors */
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #f8fafc;
            
            /* UI Elements */
            --border-color: #e2e8f0;
            --border-light: rgba(255, 255, 255, 0.7);
            --card-bg: rgba(255, 255, 255, 0.65);
            
            /* Refined Shadows */
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -2px rgba(0, 0, 0, 0.04);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --shadow-glow: 0 10px 25px -5px rgba(99, 102, 241, 0.5);
            --shadow-glow-success: 0 10px 25px -5px rgba(16, 185, 129, 0.5);
            
            /* Border Radii */
            --radius-sm: 10px;
            --radius-md: 14px;
            --radius-lg: 20px;
            --radius-xl: 28px;
            
            /* Typography */
            --font: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            
            /* Transitions */
            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        @keyframes meshAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            font-family: var(--font);
            background-color: #f1f5f9;
            /* Dynamic animated gradient background */
            background: linear-gradient(-45deg, #e2e8f0, #e0e7ff, #f8fafc, #f1f5f9);
            background-size: 400% 400%;
            animation: meshAnimation 15s ease infinite;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* Ambient floating orbs for extra premium feel on user view */
        .ambient-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
            animation: float 20s infinite ease-in-out alternate;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: rgba(99, 102, 241, 0.2);
            top: -100px; left: -100px;
        }
        .orb-2 {
            width: 300px; height: 300px;
            background: rgba(16, 185, 129, 0.15);
            bottom: -50px; right: -50px;
            animation-delay: -5s;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(50px, 50px) scale(1.1); }
        }

        /* -------------------------------------
           Utility & Component Styles
           ------------------------------------- */
        
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--border-light);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
            border-right: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: var(--shadow-md), inset 0 0 0 1px rgba(255, 255, 255, 0.4);
            border-radius: var(--radius-lg);
            padding: 2.25rem;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .glass-card:hover {
            box-shadow: var(--shadow-lg), inset 0 0 0 1px rgba(255, 255, 255, 0.5);
            transform: translateY(-3px);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.95rem 1.85rem;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 0.02em;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: var(--transition-bounce);
            text-decoration: none;
            font-family: var(--font);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(rgba(255,255,255,0.1), transparent);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn:hover::before {
            opacity: 1;
        }

        .btn:active {
            transform: scale(0.96) translateY(2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            box-shadow: var(--shadow-sm), inset 0 1px 0 rgba(255,255,255,0.2);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }

        .btn-primary:hover {
            box-shadow: var(--shadow-glow);
            transform: translateY(-3px);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), var(--success-hover));
            color: white;
            box-shadow: var(--shadow-sm), inset 0 1px 0 rgba(255,255,255,0.2);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .btn-success:hover {
            box-shadow: var(--shadow-glow-success);
            transform: translateY(-3px);
        }

        .btn-outline {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            color: var(--text-main);
            box-shadow: var(--shadow-xs);
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.95);
            border-color: #cbd5e1;
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            padding: 0.4rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            box-shadow: var(--shadow-xs);
        }

        /* -------------------------------------
           Layout Structure (Admin)
           ------------------------------------- */
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        /* Modern Floating Sidebar */
        .sidebar {
            width: 280px;
            background-color: var(--bg-sidebar);
            color: white;
            padding: 2.25rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.05);
            /* Add a subtle highlight border to the right */
            border-right: 1px solid rgba(255,255,255,0.08);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            padding: 0 0.5rem;
        }

        .sidebar-title {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, #f8fafc, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            padding: 0.9rem 1.25rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 600;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
            position: relative;
            overflow: hidden;
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            top: 0; left: 0; bottom: 0; width: 0;
            background: linear-gradient(90deg, var(--primary-soft), transparent);
            transition: width 0.3s ease;
            z-index: 0;
        }

        .sidebar-link > * {
            position: relative;
            z-index: 1;
        }

        .sidebar-link:hover {
            color: white;
        }

        .sidebar-link:hover::before {
            width: 100%;
        }

        .sidebar-link.active {
            color: var(--primary-light);
            background-color: rgba(79, 70, 229, 0.15);
            border-left: 4px solid var(--primary);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
            box-shadow: inset 4px 0 0 var(--primary);
            border-left: none; /* using inset box-shadow instead for smoother rendering */
        }
        
        .sidebar-link.active::before {
            display: none;
        }

        .admin-main {
            margin-left: 280px;
            flex: 1;
            padding: 3rem 4rem;
            min-height: 100vh;
        }

        /* -------------------------------------
           Guest / User UI
           ------------------------------------- */
        
        .guest-header {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(255,255,255,0.7);
            padding: 1.25rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .guest-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 800;
            font-size: 1.35rem;
            color: var(--bg-sidebar);
            text-decoration: none;
            letter-spacing: -0.03em;
        }

        .guest-container {
            max-width: 640px;
            margin: 4rem auto 5rem auto;
            width: 92%;
            flex: 1;
        }

        /* Footer */
        footer {
            padding: 2.5rem 2rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            background: transparent;
            margin-top: auto;
            font-weight: 500;
        }

        /* -------------------------------------
           Forms & Inputs
           ------------------------------------- */
        
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--bg-sidebar);
            margin-bottom: 0.6rem;
            letter-spacing: 0.01em;
        }

        .form-input {
            width: 100%;
            padding: 0.95rem 1.25rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background-color: rgba(255, 255, 255, 0.9);
            outline: none;
            font-family: var(--font);
            font-size: 1rem;
            color: var(--text-main);
            transition: var(--transition-smooth);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .form-input:focus {
            border-color: var(--primary);
            background-color: white;
            box-shadow: 0 0 0 4px var(--primary-soft), inset 0 1px 2px rgba(0,0,0,0.01);
        }

        .form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.25rem center;
            background-size: 1em;
            padding-right: 3rem;
            cursor: pointer;
        }

        /* -------------------------------------
           Tables (Modern Grid)
           ------------------------------------- */
        
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            background: white;
            box-shadow: var(--shadow-sm);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            text-align: left;
        }

        th {
            background-color: rgba(248, 250, 252, 0.8);
            backdrop-filter: blur(8px);
            padding: 1.25rem 1.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        td {
            padding: 1.25rem 1.5rem;
            font-size: 0.95rem;
            color: var(--text-main);
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
            font-weight: 500;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: var(--transition-smooth);
        }

        tbody tr:hover {
            background-color: #f8fafc;
            transform: scale(1.001);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: transparent; 
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }

        /* -------------------------------------
           Mobile Responsive
           ------------------------------------- */
        @media (max-width: 1024px) {
            .admin-main {
                padding: 2rem;
            }
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .admin-layout {
                flex-direction: column;
            }
            .admin-main {
                margin-left: 0;
                padding: 1.5rem;
            }
            .glass-card {
                padding: 1.75rem;
            }
        }
    </style>
</head>
<body>
    @if(Route::is('user.index') || Route::is('login') || Route::is('register'))
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    @endif

    @if(Auth::check() && !Route::is('user.index'))
        <!-- Admin Layout Shell -->
        <div class="admin-layout">
            <aside class="sidebar">
                <div class="sidebar-header">
                    <div style="background-color: var(--primary); padding: 0.5rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i data-lucide="qr-code"></i>
                    </div>
                    <span class="sidebar-title">Absensi KKN</span>
                </div>
                
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <i data-lucide="layout-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.qr') }}" class="sidebar-link {{ Route::is('admin.qr') ? 'active' : '' }}">
                            <i data-lucide="scan"></i> Proyektor QR Code
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logs') }}" class="sidebar-link {{ Route::is('admin.logs') ? 'active' : '' }}">
                            <i data-lucide="calendar"></i> Riwayat Mingguan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students') }}" class="sidebar-link {{ Route::is('admin.students') ? 'active' : '' }}">
                            <i data-lucide="users"></i> Data Mahasiswa
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" class="sidebar-link {{ Route::is('admin.settings') ? 'active' : '' }}">
                            <i data-lucide="settings"></i> Pengaturan Posko
                        </a>
                    </li>
                </ul>

                <div style="margin-top: auto;">
                    <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="width: 100%; border-color: rgba(255,255,255,0.15); color: #94a3b8; justify-content: flex-start;">
                            <i data-lucide="log-out"></i> Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <main class="admin-main">
                @yield('content')
            </main>
        </div>
    @else
        <!-- Guest / User Layout Shell -->
        <header class="guest-header">
            <a href="/" class="guest-logo">
                <i data-lucide="map-pin" style="color: var(--primary);"></i>
                <span>{{ \App\Models\Setting::getValue('kkn_name', 'KKN Posko') }}</span>
            </a>
            @if(Auth::check())
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-radius: 12px;">
                        <i data-lucide="user"></i> Admin Panel
                    </a>
                @else
                    <a href="{{ route('user.logout') }}" class="btn btn-outline btn-sm" style="padding: 0.5rem 1rem; font-size: 0.85rem; color: #ef4444; border-color: rgba(239, 68, 68, 0.3); border-radius: 12px;" onclick="window.location.href='{{ route('user.logout') }}'; return false;">
                        <i data-lucide="log-out"></i> Logout
                    </a>
                @endif
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-outline btn-sm" style="padding: 0.5rem 1rem; font-size: 0.85rem; border-radius: 12px;">
                    <i data-lucide="log-in"></i> Login
                </a>
            @endif
        </header>

        <div class="guest-container">
            @yield('content')
        </div>
    @endif

    <!-- Lucide Icon Initialization -->
    <script>
        lucide.createIcons();
    </script>
    
    @yield('scripts')
</body>
</html>
