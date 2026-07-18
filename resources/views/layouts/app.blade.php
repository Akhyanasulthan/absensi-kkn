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
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Custom CSS Design System - Toy Story Theme -->
    <style>
        :root {
            /* Toy Story Color Palette */
            --woody-yellow: #FDE047;
            --woody-red: #EF4444;
            --woody-red-dark: #B91C1C;
            --woody-brown: #8B4513;
            --woody-blue: #3B82F6;
            
            --buzz-green: #84CC16;
            --buzz-green-dark: #4D7C0F;
            --buzz-purple: #A855F7;
            
            --andy-blue: #4CB1E1;
            
            /* Text Colors */
            --text-main: #1E3A8A; /* Deep blue */
            --text-muted: #475569;
            
            /* Typography */
            --font-heading: 'Fredoka One', cursive;
            --font-body: 'Nunito', sans-serif;
            
            /* Transitions */
            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: var(--font-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            line-height: 1.6;
            
            /* Andy's Room Wallpaper Blue */
            background-color: var(--andy-blue);
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(255,255,255,0.8) 10px, transparent 11px),
                radial-gradient(circle at 22% 29%, rgba(255,255,255,0.8) 15px, transparent 16px),
                radial-gradient(circle at 25% 30%, rgba(255,255,255,0.8) 12px, transparent 13px),
                radial-gradient(circle at 23% 32%, rgba(255,255,255,0.8) 14px, transparent 15px),
                
                radial-gradient(circle at 75% 60%, rgba(255,255,255,0.6) 10px, transparent 11px),
                radial-gradient(circle at 77% 59%, rgba(255,255,255,0.6) 15px, transparent 16px),
                radial-gradient(circle at 80% 60%, rgba(255,255,255,0.6) 12px, transparent 13px),
                radial-gradient(circle at 78% 62%, rgba(255,255,255,0.6) 14px, transparent 15px);
            background-size: 200px 200px;
        }

        /* -------------------------------------
           Utility & Toy Component Styles
           ------------------------------------- */
        
        .toy-card {
            background: white;
            border: 4px solid var(--woody-blue);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 20px rgba(0,0,0,0.15), inset 0 -4px 0 rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .toy-title {
            font-family: var(--font-heading);
            color: var(--woody-blue);
            letter-spacing: 1px;
        }

        /* Buzz Lightyear Button */
        .toy-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: var(--buzz-green);
            border: 4px solid var(--buzz-green-dark);
            border-bottom-width: 8px;
            color: white;
            font-family: var(--font-heading);
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            border-radius: 99px;
            cursor: pointer;
            transition: all 0.1s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-shadow: 1px 1px 0px var(--buzz-green-dark);
            text-decoration: none;
            position: relative;
        }

        .toy-btn::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 3px solid var(--buzz-purple);
            border-radius: 99px;
            opacity: 0.5;
            pointer-events: none;
        }

        .toy-btn:hover {
            background: #A3E635;
            transform: translateY(-2px);
            border-bottom-width: 10px;
            margin-bottom: -2px;
        }

        .toy-btn:active {
            background: #65A30D;
            transform: translateY(4px);
            border-bottom-width: 4px;
            margin-bottom: 4px;
        }

        .toy-btn-danger {
            background: var(--woody-red);
            border-color: var(--woody-red-dark);
            text-shadow: 1px 1px 0px var(--woody-red-dark);
        }
        .toy-btn-danger::after {
            border-color: #FCA5A5;
        }
        .toy-btn-danger:hover {
            background: #F87171;
        }
        .toy-btn-danger:active {
            background: #991B1B;
        }

        .toy-btn-small {
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
            border-width: 3px;
            border-bottom-width: 6px;
        }
        .toy-btn-small:hover {
            border-bottom-width: 8px;
            margin-bottom: -2px;
        }
        .toy-btn-small:active {
            border-bottom-width: 3px;
            margin-bottom: 3px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            padding: 0.3rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-family: var(--font-heading);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            border: 2px solid rgba(0,0,0,0.1);
            color: white;
            text-shadow: 1px 1px 0 rgba(0,0,0,0.2);
        }
        
        .badge-success { background: var(--buzz-green); }
        .badge-warning { background: var(--woody-yellow); color: var(--woody-brown); text-shadow: none; }
        .badge-danger { background: var(--woody-red); }

        /* Forms */
        .toy-label {
            font-family: var(--font-heading);
            color: var(--text-main);
            font-size: 1rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .toy-input {
            width: 100%;
            background: white;
            border: 3px solid var(--woody-blue);
            border-radius: 12px;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            font-family: var(--font-body);
            font-weight: 700;
            color: var(--text-main);
            transition: var(--transition-bounce);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }

        .toy-input:focus {
            outline: none;
            border-color: var(--buzz-purple);
            transform: scale(1.01);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1), 0 0 0 4px rgba(168, 85, 247, 0.3);
        }

        select.toy-input {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%233B82F6'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='3' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1em;
            padding-right: 2.5rem;
            cursor: pointer;
        }

        /* -------------------------------------
           Layout Structure (Admin)
           ------------------------------------- */
        
        .admin-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        /* Wooden Plank Sidebar (Woody Theme) */
        .sidebar {
            width: 280px;
            background-color: var(--woody-yellow);
            /* Wooden texture look */
            background-image: 
                repeating-linear-gradient(
                    to right,
                    var(--woody-yellow) 0px,
                    var(--woody-yellow) 20px,
                    #FCE788 20px,
                    #FCE788 40px
                );
            border-right: 8px solid var(--woody-brown);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 10px 0 15px rgba(0,0,0,0.2);
        }

        /* Cow print header inside sidebar */
        .sidebar-header {
            background: white;
            border: 4px solid var(--woody-red);
            border-radius: 16px;
            padding: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            position: relative;
            box-shadow: inset 0 0 0 4px white, inset 0 0 0 6px var(--woody-red);
        }
        
        .sidebar-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(#000 15%, transparent 16%), radial-gradient(#000 15%, transparent 16%);
            background-size: 40px 40px;
            background-position: 0 0, 20px 20px;
            opacity: 0.1;
            z-index: 0;
            pointer-events: none;
        }

        .sidebar-title {
            font-family: var(--font-heading);
            font-size: 1.4rem;
            color: var(--woody-red);
            text-transform: uppercase;
            z-index: 1;
            text-shadow: 1px 1px 0 white;
        }
        
        .sidebar-icon {
            z-index: 1;
            color: var(--woody-blue);
        }

        .sidebar-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            color: var(--woody-brown);
            text-decoration: none;
            border-radius: 12px;
            font-family: var(--font-heading);
            font-size: 1rem;
            transition: all 0.2s ease;
            border: 3px solid transparent;
        }

        .sidebar-link:hover {
            background-color: white;
            border-color: var(--woody-brown);
            transform: scale(1.05) rotate(-2deg);
        }

        .sidebar-link.active {
            background-color: var(--woody-red);
            color: white;
            border-color: var(--woody-red-dark);
            box-shadow: 0 4px 0 var(--woody-red-dark);
            transform: scale(1.05);
        }

        .admin-main {
            margin-left: 280px;
            flex: 1;
            padding: 3rem 4rem;
            min-height: 100vh;
            /* White overlay to make content readable over clouds */
            background: rgba(255,255,255,0.7);
        }

        /* -------------------------------------
           Guest / User UI
           ------------------------------------- */
        
        .guest-header {
            background: var(--woody-yellow);
            border-bottom: 6px solid var(--woody-red);
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .guest-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-family: var(--font-heading);
            font-size: 1.5rem;
            color: var(--woody-blue);
            text-decoration: none;
            text-shadow: 1px 1px 0 white;
            letter-spacing: 1px;
        }

        .guest-container {
            max-width: 640px;
            margin: 3rem auto 4rem auto;
            width: 92%;
            flex: 1;
        }

        /* Footer */
        footer {
            padding: 2rem;
            text-align: center;
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            margin-top: auto;
        }

        /* -------------------------------------
           Tables (Toy Style)
           ------------------------------------- */
        
        .table-container {
            overflow-x: auto;
            border: 4px solid var(--woody-blue);
            border-radius: 16px;
            background: white;
            box-shadow: 0 8px 0 rgba(59, 130, 246, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th {
            background-color: var(--woody-blue);
            color: white;
            font-family: var(--font-heading);
            padding: 1.25rem;
            font-size: 0.95rem;
            letter-spacing: 1px;
            border-bottom: 4px solid #1D4ED8;
        }

        td {
            padding: 1.25rem;
            font-size: 1rem;
            color: var(--text-main);
            border-bottom: 2px dashed #BFDBFE;
            font-weight: 700;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:nth-child(even) {
            background-color: #EFF6FF;
        }

        tbody tr:hover {
            background-color: #DBEAFE;
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
            .toy-card {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    @if(Auth::check() && !Route::is('user.index') && !Route::is('login') && !Route::is('admin.login'))
        <!-- Admin Layout Shell -->
        <div class="admin-layout">
            <aside class="sidebar">
                <div class="sidebar-header">
                    <i data-lucide="star" class="sidebar-icon" style="width: 28px; height: 28px; fill: #FBBF24;"></i>
                    <span class="sidebar-title">Absensi</span>
                </div>
                
                <ul class="sidebar-menu">
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <i data-lucide="layout-dashboard"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.qr') }}" class="sidebar-link {{ Route::is('admin.qr') ? 'active' : '' }}">
                            <i data-lucide="scan"></i> Proyektor QR
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.logs') }}" class="sidebar-link {{ Route::is('admin.logs') ? 'active' : '' }}">
                            <i data-lucide="calendar"></i> Riwayat
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.students') }}" class="sidebar-link {{ Route::is('admin.students') ? 'active' : '' }}">
                            <i data-lucide="users"></i> Pemain
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.settings') }}" class="sidebar-link {{ Route::is('admin.settings') ? 'active' : '' }}">
                            <i data-lucide="settings"></i> Pengaturan
                        </a>
                    </li>
                </ul>

                <div style="margin-top: auto;">
                    <form action="{{ route('admin.logout') }}" method="POST" id="logout-form">
                        @csrf
                        <button type="submit" class="toy-btn toy-btn-danger" style="width: 100%;">
                            <i data-lucide="log-out"></i> KELUAR
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
                <i data-lucide="box" style="color: var(--woody-red); fill: var(--woody-yellow);"></i>
                <span>{{ \App\Models\Setting::getValue('kkn_name', 'KKN Posko') }}</span>
            </a>
            @if(Auth::check())
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="toy-btn toy-btn-small">
                        <i data-lucide="user"></i> Admin
                    </a>
                @else
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="toy-btn toy-btn-small toy-btn-danger">
                            <i data-lucide="log-out"></i> Keluar
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="toy-btn toy-btn-small">
                    <i data-lucide="shield"></i> Admin
                </a>
            @endif
        </header>

        <div class="guest-container">
            @yield('content')
        </div>
        
        @if(Route::is('user.index'))
        <footer>
            Menuju Tak Terbatas dan Melampauinya! 🚀<br>
            © {{ date('Y') }} Tim Posko
        </footer>
        @endif
    @endif

    <!-- Lucide Icon Initialization -->
    <script>
        lucide.createIcons();
    </script>
    
    @yield('scripts')
</body>
</html>
