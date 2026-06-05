<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Noteku')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #0d0d0d;
            --bg-2:     #141414;
            --bg-3:     #1c1c1c;
            --bg-hover: #1f1f1f;
            --border:   #232323;
            --border-2: #2a2a2a;
            --text:     #e8e6e0;
            --text-2:   #888;
            --text-3:   #444;
            --accent:   #378ADD;
            --red:      #E24B4A;
            --navbar-h: 52px;
        }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'Geist', sans-serif;
            font-size: 14px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ══════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════ */
        .navbar {
            height: var(--navbar-h);
            background: var(--bg-2);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 20px;
            gap: 8px;
            position: sticky;
            top: 0;
            z-index: 200;
        }

        /* Logo */
        .navbar-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            flex-shrink: 0;
            margin-right: 8px;
        }
        .navbar-logo-icon {
            width: 28px;
            height: 28px;
            background: #1e1e1e;
            border: 1px solid var(--border-2);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .navbar-logo-icon svg { width: 14px; height: 14px; stroke: #c8c5be; }
        .navbar-logo-text {
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            letter-spacing: -0.3px;
        }

        /* Nav links */
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 2px;
            flex: 1;
        }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            color: var(--text-2);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 400;
            transition: all 0.15s;
            white-space: nowrap;
            border: none;
            background: none;
            cursor: pointer;
            font-family: inherit;
        }
        .nav-link svg { width: 14px; height: 14px; flex-shrink: 0; }
        .nav-link:hover { background: var(--bg-hover); color: var(--text); }
        .nav-link.active {
            background: var(--bg-3);
            color: var(--text);
        }
        .nav-link.danger { color: var(--red); }
        .nav-link.danger:hover { background: rgba(226,75,74,0.08); color: var(--red); }

        /* New Note button */
        .btn-new-note {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: var(--text);
            color: #0d0d0d;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            font-family: inherit;
            transition: background 0.15s;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .btn-new-note svg { width: 14px; height: 14px; }
        .btn-new-note:hover { background: #fff; }

        /* Right side */
        .navbar-right {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-left: 16px;
            flex-shrink: 0;
        }

        /* Bell */
        .navbar-icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: none;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-2);
            transition: background 0.15s, color 0.15s;
            position: relative;
        }
        .navbar-icon-btn svg { width: 16px; height: 16px; }
        .navbar-icon-btn:hover { background: var(--bg-3); color: var(--text); }
        .notif-dot {
            position: absolute;
            top: 7px; right: 7px;
            width: 6px; height: 6px;
            background: var(--accent);
            border-radius: 50%;
            border: 1.5px solid var(--bg-2);
        }

        /* User pill */
        .user-pill {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 10px 5px 6px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--bg-3);
            cursor: pointer;
            transition: border-color 0.15s;
            text-decoration: none;
        }
        .user-pill:hover { border-color: var(--border-2); }
        .user-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #2a3d55;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 600;
            color: #7ab3e0;
            flex-shrink: 0;
            overflow: hidden;
        }
        .user-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .user-info { display: flex; flex-direction: column; }
        .user-name  { font-size: 12.5px; font-weight: 500; color: var(--text); line-height: 1.2; }
        .user-role  { font-size: 10.5px; color: var(--text-3); line-height: 1.2; }
        .user-pill-caret svg { width: 12px; height: 12px; stroke: var(--text-3); }

        /* Dropdown menu */
        .user-dropdown-wrap { position: relative; }
        .user-dropdown {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 200px;
            background: var(--bg-2);
            border: 1px solid var(--border-2);
            border-radius: 12px;
            padding: 6px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.5);
            z-index: 300;
        }
        .user-dropdown-wrap:focus-within .user-dropdown,
        .user-dropdown-wrap.open .user-dropdown { display: block; }
        .dropdown-divider { height: 1px; background: var(--border); margin: 4px 0; }
        .dropdown-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 7px 10px;
            border-radius: 8px;
            color: var(--text-2);
            text-decoration: none;
            font-size: 13px;
            transition: all 0.12s;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
        }
        .dropdown-link svg { width: 14px; height: 14px; }
        .dropdown-link:hover { background: var(--bg-3); color: var(--text); }
        .dropdown-link.danger { color: var(--red); }
        .dropdown-link.danger:hover { background: rgba(226,75,74,0.08); }

        /* ══════════════════════════════════════
           SEARCH BAR (di bawah navbar)
        ══════════════════════════════════════ */
        .searchbar-wrap {
            background: var(--bg);
            padding: 14px 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .searchbar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-2);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            height: 38px;
            flex: 1;
            max-width: 520px;
            transition: border-color 0.15s;
        }
        .searchbar:focus-within { border-color: var(--border-2); }
        .searchbar svg { width: 14px; height: 14px; stroke: var(--text-3); flex-shrink: 0; }
        .searchbar input {
            background: none;
            border: none;
            outline: none;
            color: var(--text);
            font-size: 13.5px;
            font-family: inherit;
            flex: 1;
        }
        .searchbar input::placeholder { color: var(--text-3); }

        /* ══════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════ */
        .main-content {
            flex: 1;
            padding: 24px 24px 40px;
        }

        /* ══════════════════════════════════════
           PAGE FOOTER
        ══════════════════════════════════════ */
        .page-footer {
            border-top: 1px solid var(--border);
            padding: 14px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-3);
        }
        .page-footer a { color: var(--text-3); text-decoration: none; }
        .page-footer a:hover { color: var(--text-2); }
        .page-footer-links { display: flex; gap: 16px; }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="navbar">

    {{-- Logo --}}
    <a href="{{ route('dashboard') }}" class="navbar-logo">
        <div class="navbar-logo-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
        </div>
        <span class="navbar-logo-text">Noteku</span>
    </a>

    {{-- Nav links --}}
    <div class="navbar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Home
        </a>
        <a href="{{ route('archive') }}" class="nav-link {{ request()->routeIs('archive') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
            Archive
        </a>
        <a href="{{ route('trash') }}" class="nav-link {{ request()->routeIs('trash') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
            Trash
        </a>
        <a href="{{ route('categories') }}" class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
            Categories
        </a>
    </div>

    {{-- New Note button --}}
    <a href="{{ route('notes.create') }}" class="btn-new-note">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Note
    </a>

    {{-- Right --}}
    <div class="navbar-right">

        {{-- Bell --}}
        <button class="navbar-icon-btn" aria-label="Notifikasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="notif-dot"></span>
        </button>

        {{-- User pill + dropdown --}}
        @auth
        <div class="user-dropdown-wrap" id="userDropWrap">
            <button class="user-pill" onclick="toggleDrop()" aria-label="User menu">
                <div class="user-avatar">
                    @if(auth()->user()->avatar)
                        <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="avatar">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </div>
                <div class="user-info">
                    <span class="user-name">{{ auth()->user()->name }}</span>
                    <span class="user-role">Premium Plan</span>
                </div>
                <span class="user-pill-caret">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="6 9 12 15 18 9"/></svg>
                </span>
            </button>

            <div class="user-dropdown" id="userDrop">
                <a href="{{ route('profile') }}" class="dropdown-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile
                </a>
                <a href="#" class="dropdown-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93l-1.41 1.41M4.93 4.93l1.41 1.41M1 12h2M21 12h2M4.93 19.07l1.41-1.41M19.07 19.07l-1.41-1.41M12 1v2M12 21v2"/></svg>
                    Settings
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-link danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Sign Out
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>

{{-- ── SEARCH BAR ── --}}
<div class="searchbar-wrap">
    <div class="searchbar">
        <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" placeholder="Search across your workspace...">
    </div>
</div>

{{-- ── MAIN CONTENT ── --}}
<div class="main-content">
    @yield('content')
</div>

{{-- ── FOOTER ── --}}
<footer class="page-footer">
    <span>© 2024 Noteku. Premium Workspace.</span>
    <div class="page-footer-links">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Help Center</a>
    </div>
</footer>

<script>
function toggleDrop() {
    const w = document.getElementById('userDropWrap');
    w.classList.toggle('open');
}
document.addEventListener('click', function(e) {
    const w = document.getElementById('userDropWrap');
    if (w && !w.contains(e.target)) w.classList.remove('open');
});
</script>

@livewireScripts
</body>
</html>