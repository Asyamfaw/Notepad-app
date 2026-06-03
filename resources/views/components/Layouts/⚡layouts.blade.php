<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Noteku' }}</title>
    @livewireStyles
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar">
        <div class="nav-left">
            <a href="{{ route('dashboard') }}" class="nav-logo">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
                Noteku
            </a>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                Home
            </a>
            <a href="{{ route('archive') }}" class="nav-link {{ request()->routeIs('archive') ? 'active' : '' }}">
                Archive
            </a>
            <a href="{{ route('trash') }}" class="nav-link {{ request()->routeIs('trash') ? 'active' : '' }}">
                Trash
            </a>
            <a href="{{ route('categories') }}" class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}">
                Categories
            </a>
        </div>

        <div class="nav-right">
            {{-- Search --}}
            <div class="nav-search">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" placeholder="Search...">
            </div>

            {{-- Tambah Note --}}
            <a href="{{ route('notes.create') }}" class="btn-new-note">
                + Tambah Note
            </a>

            {{-- User Dropdown --}}
            <div class="user-menu" x-data="{ open: false }">
                <button @click="open = !open" class="user-chip">
                    @if(Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}" class="avatar-img">
                    @else
                        <div class="avatar-placeholder">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <span>{{ Auth::user()->name }}</span>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" class="dropdown">
                    <a href="{{ route('profile') }}" class="dropdown-item">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="main-content">
        {{ $slot }}
    </main>

    @livewireScripts
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background: #0e0e0e;
            color: #e0ddd8;
            font-family: 'Instrument Sans', 'Inter', sans-serif;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            height: 52px;
            background: #111;
            border-bottom: 1px solid #1e1e1e;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .nav-left {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .nav-logo {
            display: flex;
            align-items: center;
            gap: 7px;
            font-size: 15px;
            font-weight: 500;
            color: #e0ddd8;
            text-decoration: none;
            margin-right: 16px;
        }
        .nav-link {
            font-size: 13px;
            color: #666;
            padding: 5px 10px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.15s;
        }
        .nav-link:hover { color: #aaa; background: #1a1a1a; }
        .nav-link.active { color: #e0ddd8; background: #1e1e1e; }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 7px;
            padding: 0 12px;
            height: 32px;
            color: #555;
        }
        .nav-search input {
            background: none;
            border: none;
            outline: none;
            font-size: 13px;
            color: #e0ddd8;
            width: 180px;
        }
        .nav-search input::placeholder { color: #555; }

        .btn-new-note {
            display: flex;
            align-items: center;
            gap: 5px;
            height: 32px;
            padding: 0 14px;
            background: #e0ddd8;
            color: #111;
            border-radius: 7px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: opacity 0.15s;
        }
        .btn-new-note:hover { opacity: 0.85; }

        /* User Menu */
        .user-menu { position: relative; }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
            border-radius: 99px;
            cursor: pointer;
            color: #999;
            font-size: 13px;
        }
        .avatar-img {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            object-fit: cover;
        }
        .avatar-placeholder {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background: #2a2a2a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 500;
            color: #888;
        }
        .dropdown {
            position: absolute;
            right: 0;
            top: calc(100% + 6px);
            background: #161616;
            border: 1px solid #2a2a2a;
            border-radius: 10px;
            padding: 6px;
            min-width: 140px;
            z-index: 50;
        }
        .dropdown-item {
            display: block;
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            color: #999;
            text-decoration: none;
            border-radius: 6px;
            background: none;
            border: none;
            cursor: pointer;
            text-align: left;
            transition: all 0.15s;
        }
        .dropdown-item:hover { background: #1e1e1e; color: #e0ddd8; }

        .main-content {
            padding: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>

</body>
</html>