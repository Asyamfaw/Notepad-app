<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Notes App')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    @livewireStyles
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg:#0a0a0a; --bg-2:#111111; --bg-3:#1a1a1a;
            --border:#222222; --text:#e8e6e0; --text-2:#999; --text-3:#555;
            --accent:#378ADD; --sidebar-w:220px;
        }
        body { background:var(--bg); color:var(--text); font-family:'Inter',sans-serif; font-size:14px; display:flex; min-height:100vh; }
        .sidebar { width:var(--sidebar-w); background:var(--bg-2); border-right:1px solid var(--border); display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100; }
        .sidebar-logo { display:flex; align-items:center; gap:10px; padding:20px 18px 16px; border-bottom:1px solid var(--border); }
        .sidebar-logo-icon { width:32px; height:32px; background:linear-gradient(135deg,var(--accent),#5BA4E6); border-radius:8px; display:flex; align-items:center; justify-content:center; }
        .sidebar-logo-icon svg { color:#fff; }
        .sidebar-logo-text { font-size:15px; font-weight:600; color:var(--text); letter-spacing:-0.3px; }
        .sidebar-nav { flex:1; padding:12px 10px; display:flex; flex-direction:column; gap:2px; overflow-y:auto; }
        .nav-section { font-size:10px; font-weight:500; letter-spacing:0.8px; color:var(--text-3); text-transform:uppercase; padding:8px 8px 4px; margin-top:8px; }
        .nav-link { display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:8px; color:var(--text-2); text-decoration:none; font-size:13.5px; transition:all 0.15s; cursor:pointer; border:none; background:none; width:100%; text-align:left; }
        .nav-link:hover { background:var(--bg-3); color:var(--text); }
        .nav-link.active { background:rgba(55,138,221,0.12); color:var(--accent); }
        .sidebar-footer { padding:12px 10px; border-top:1px solid var(--border); }
        .user-card { display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:8px; }
        .user-avatar { width:30px; height:30px; border-radius:50%; background:linear-gradient(135deg,var(--accent),#5BA4E6); display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; color:#fff; flex-shrink:0; overflow:hidden; }
        .user-avatar img { width:100%; height:100%; object-fit:cover; }
        .user-name { font-size:12.5px; font-weight:500; color:var(--text); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .user-email { font-size:11px; color:var(--text-3); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .main-wrap { margin-left:var(--sidebar-w); flex:1; display:flex; flex-direction:column; }
        .main-content { flex:1; padding:28px 32px; }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <span class="sidebar-logo-text">Notes App</span>
    </div>
    <nav class="sidebar-nav">
        <span class="nav-section">Utama</span>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>Dashboard</a>
        <a href="{{ route('notes.create') }}" class="nav-link {{ request()->routeIs('notes.create') || request()->routeIs('notes.edit') ? 'active' : '' }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>Tambah Catatan</a>
        <span class="nav-section">Kelola</span>
        <a href="{{ route('categories') }}" class="nav-link {{ request()->routeIs('categories') ? 'active' : '' }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Kategori & Tag</a>
        <a href="{{ route('archive') }}" class="nav-link {{ request()->routeIs('archive') ? 'active' : '' }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>Arsip</a>
        <a href="{{ route('trash') }}" class="nav-link {{ request()->routeIs('trash') ? 'active' : '' }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>Sampah</a>
    </nav>
    <div class="sidebar-footer">
        <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}" style="margin-bottom:4px;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>Profil</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link" style="color:#E24B4A;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>Keluar</button>
        </form>
        @auth
        <div class="user-card" style="margin-top:8px;">
            <div class="user-avatar">
                @if(auth()->user()->avatar)
                    <img src="{{ Storage::url(auth()->user()->avatar) }}" alt="avatar">
                @else
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
        @endauth
    </div>
</aside>

<div class="main-wrap">
    <div class="main-content">
        @yield('content')
    </div>
</div>
@livewireScripts
</body>
</html>
