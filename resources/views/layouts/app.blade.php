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

    <!-- Custom CSS Design System -->
    <style>
        :root {
            /* Premium Color Palette */
            --primary: #6366f1; /* Indigo 500 */
            --primary-hover: #4f46e5; /* Indigo 600 */
            --primary-light: #e0e7ff; /* Indigo 100 */
            --primary-soft: rgba(99, 102, 241, 0.1);
            
            --success: #10b981;
            --success-hover: #059669;
            --success-light: #d1fae5;
            
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            
            --danger: #ef4444;
            --danger-light: #fee2e2;
            
            /* Backgrounds & Surfaces */
            --bg-main: #f8fafc; /* Slate 50 */
            --bg-sidebar: #0f172a; /* Slate 900 */
            --bg-sidebar-hover: #1e293b; /* Slate 800 */
            
            /* Text Colors */
            --text-main: #0f172a;
            --text-muted: #64748b;
            --text-light: #f8fafc;
            
            /* UI Elements */
            --border-color: #e2e8f0; /* Slate 200 */
            --border-light: rgba(255, 255, 255, 0.5);
            --card-bg: rgba(255, 255, 255, 0.9);
            
            /* Refined Shadows */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 25px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05);
            --shadow-glow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            
            /* Border Radii */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            
            /* Typography */
            --font: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            
            /* Transitions */
            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            --transition-smooth: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font);
            background-color: var(--bg-main);
            background-image: 
                radial-gradient(at 0% 0%, hsla(253,16%,7%,0.02) 0, transparent 50%), 
                radial-gradient(at 50% 0%, hsla(225,39%,30%,0.02) 0, transparent 50%), 
                radial-gradient(at 100% 0%, hsla(339,49%,30%,0.02) 0, transparent 50%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            line-height: 1.6;
        }

        /* -------------------------------------
           Utility & Component Styles
           ------------------------------------- */
        
        .glass-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-light);
            box-shadow: var(--shadow-lg);
            border-radius: var(--radius-lg);
            padding: 2rem;
            transition: var(--transition-smooth);
        }
        
        .glass-card:hover {
            box-shadow: 0 15px 35px -5px rgb(0 0 0 / 0.07);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            padding: 0.8rem 1.75rem;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: var(--radius-md);
            border: none;
            cursor: pointer;
            transition: var(--transition-bounce);
            text-decoration: none;
            font-family: var(--font);
            position: relative;
            overflow: hidden;
        }

        .btn:active {
            transform: scale(0.97);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-hover));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-primary:hover {
            box-shadow: var(--shadow-glow);
            transform: translateY(-2px);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success), var(--success-hover));
            color: white;
            box-shadow: var(--shadow-md);
        }

        .btn-success:hover {
            box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: white;
            border: 1px solid var(--border-color);
            color: var(--text-main);
            box-shadow: var(--shadow-sm);
        }

        .btn-outline:hover {
            background-color: var(--bg-main);
            border-color: #cbd5e1;
            transform: translateY(-2px);
        }
        
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.35rem 0.85rem;
            border-radius: 9999px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* -------------------------------------
           Layout Structure (Admin)
           ------------------------------------- */
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        .sidebar {
            width: 280px;
            background-color: var(--bg-sidebar);
            color: white;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0 0.5rem;
        }

        .sidebar-title {
            font-size: 1.35rem;
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #e0e7ff, #a5b4fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1.25rem;
            color: #94a3b8;
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            font-size: 0.95rem;
            transition: var(--transition-smooth);
        }

        .sidebar-link:hover {
            color: white;
            background-color: var(--bg-sidebar-hover);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background-color: var(--primary-soft);
            color: var(--primary-light);
            border-left: 3px solid var(--primary);
            border-radius: 0 var(--radius-md) var(--radius-md) 0;
        }

        .admin-main {
            margin-left: 280px;
            flex: 1;
            padding: 2.5rem 3.5rem;
            background-color: var(--bg-main);
            min-height: 100vh;
        }

        /* -------------------------------------
           Guest / User UI
           ------------------------------------- */
        
        .guest-header {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255,255,255,0.5);
            padding: 1.25rem 2rem;
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
            font-size: 1.25rem;
            color: var(--bg-sidebar);
            text-decoration: none;
            letter-spacing: -0.02em;
        }

        .guest-container {
            max-width: 640px;
            margin: 3rem auto 4rem auto;
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
        }

        /* -------------------------------------
           Forms & Inputs
           ------------------------------------- */
        
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 0.6rem;
        }

        .form-input {
            width: 100%;
            padding: 0.85rem 1.25rem;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            background-color: white;
            outline: none;
            font-family: var(--font);
            font-size: 1rem;
            color: var(--text-main);
            transition: var(--transition-smooth);
            box-shadow: var(--shadow-sm);
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        /* Select styling override */
        select.form-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path সীম>%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem;
        }

        /* -------------------------------------
           Tables
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
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: #f8fafc;
            padding: 1.25rem 1.5rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        td {
            padding: 1.25rem 1.5rem;
            font-size: 0.95rem;
            color: var(--text-main);
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr {
            transition: var(--transition-smooth);
        }

        tbody tr:hover {
            background-color: #f8fafc;
        }

        /* -------------------------------------
           Mobile Responsive
           ------------------------------------- */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            .admin-layout {
                flex-direction: column;
            }
            .admin-main {
                margin-left: 0;
                padding: 1.5rem;
            }
            .glass-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

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
            @if(Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="user"></i> Admin Panel
                </a>
            @elseif(Auth::check())
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-outline btn-sm" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                        <i data-lucide="log-out"></i> Logout
                    </button>
                </form>
            @else
                <a href="{{ route('admin.login') }}" class="btn btn-outline btn-sm" style="padding: 0.5rem 1rem; font-size: 0.85rem;">
                    <i data-lucide="log-in"></i> Admin Login
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
