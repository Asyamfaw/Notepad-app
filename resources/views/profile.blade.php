@extends('layouts.app')
@section('title', 'Profile — Notes App')
@section('content')
@php $user = auth()->user(); @endphp

<div class="profile-page">

    {{-- ── Flash ── --}}
    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Page Header ── --}}
    <div class="page-header">
        <h1 class="page-title">
            <span class="title-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </span>
            Profile
        </h1>
        <p class="page-sub">Manage your account information</p>
    </div>

    <div class="profile-grid">

        {{-- ── Left Column ── --}}
        <div class="left-col">

            {{-- Avatar Card --}}
            <div class="avatar-card">
                <div class="avatar-glow"></div>
                <div class="big-avatar">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="avatar">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="avatar-info">
                    <div class="avatar-name">{{ $user->name }}</div>
                    <div class="avatar-email">{{ $user->email }}</div>
                    @if($user->bio)
                        <p class="avatar-bio">{{ $user->bio }}</p>
                    @endif
                </div>
                @if($user->created_at)
                    <div class="avatar-joined">
                        <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <path d="M16 2v4M8 2v4M3 10h18"/>
                        </svg>
                        Joined {{ $user->created_at->format('F Y') }}
                    </div>
                @endif
            </div>

            {{-- Stats Card --}}
            <div class="stats-card">
                <div class="stat-item">
                    <div class="stat-icon stat-icon--total">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-num">{{ $stats['total_notes'] }}</div>
                        <div class="stat-label">Total Notes</div>
                    </div>
                </div>
                <div class="stats-divider"></div>
                <div class="stat-item">
                    <div class="stat-icon stat-icon--pinned">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="17" x2="12" y2="22"/>
                            <path d="M5 17h14v-1.76a2 2 0 00-1.11-1.79l-1.78-.9A2 2 0 0115 10.76V6h1a2 2 0 000-4H8a2 2 0 000 4h1v4.76a2 2 0 01-1.11 1.79l-1.78.9A2 2 0 005 15.24z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-num stat-num--pinned">{{ $stats['pinned_notes'] }}</div>
                        <div class="stat-label">Pinned</div>
                    </div>
                </div>
                <div class="stats-divider"></div>
                <div class="stat-item">
                    <div class="stat-icon stat-icon--archived">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="21 8 21 21 3 21 3 8"/>
                            <rect x="1" y="3" width="22" height="5"/>
                            <line x1="10" y1="12" x2="14" y2="12"/>
                        </svg>
                    </div>
                    <div>
                        <div class="stat-num stat-num--archived">{{ $stats['archived_notes'] }}</div>
                        <div class="stat-label">Archived</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── Right Column: Edit Form ── --}}
        <div class="right-col">
            <div class="form-card">
                <div class="form-card-header">
                    <h2 class="form-section-title">Edit Profile</h2>
                    <p class="form-section-sub">Update your personal information</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}"
                      enctype="multipart/form-data" class="edit-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name"
                               value="{{ old('name', $user->name) }}"
                               class="form-input {{ $errors->has('name') ? 'input-error' : '' }}"
                               placeholder="Your full name">
                        @error('name')
                            <span class="form-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email"
                               value="{{ old('email', $user->email) }}"
                               class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                               placeholder="your@email.com">
                        @error('email')
                            <span class="form-error">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="12" y1="8" x2="12" y2="12"/>
                                    <line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="3" class="form-input form-textarea"
                                  placeholder="Tell a bit about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Profile Photo</label>
                        @if($user->avatar)
                            <div class="current-avatar-preview">
                                <img src="{{ Storage::url($user->avatar) }}" alt="Current avatar">
                                <span>Current photo · Upload new to replace</span>
                            </div>
                        @endif
                        <div class="file-input-wrap">
                            <input type="file" name="avatar" accept="image/*"
                                   class="file-input" id="avatar-input">
                            <label for="avatar-input" class="file-label">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                Choose photo
                            </label>
                            <span class="file-name" id="file-name-display">No file chosen</span>
                        </div>
                        @error('avatar') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-separator"></div>

                    <div class="form-actions">
                        <button type="button" onclick="window.history.back()" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="submit" class="btn-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M20 6L9 17l-5-5"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('avatar-input');
    const display = document.getElementById('file-name-display');
    if (input && display) {
        input.addEventListener('change', () => {
            display.textContent = input.files[0]?.name || 'No file chosen';
        });
    }
});
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700&display=swap');

.profile-page {
    font-family: 'Hanken Grotesk', 'Inter', sans-serif;
    --primary: #23A9BD;
    --primary-dim: rgba(35, 169, 189, 0.1);
    --primary-border: rgba(35, 169, 189, 0.22);
    --surface-2: #131b24;
    --border-solid: #1e2d3d;
    --text-primary: #e8f4f8;
    --text-secondary: #7a9bb0;
    --text-muted: #3a5568;
    --danger: #E24B4A;
    --warning: #EF9F27;
    --success: #23A9BD;
}

.flash-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(35,169,189,0.08); border: 1px solid rgba(35,169,189,0.25);
    color: #23A9BD; font-size: 13px; padding: 10px 14px;
    border-radius: 10px; margin-bottom: 24px;
}

.page-header { margin-bottom: 28px; }
.page-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 600; color: var(--text-primary);
    letter-spacing: -0.4px; margin: 0 0 6px;
}
.title-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--primary-dim); border: 1px solid var(--primary-border);
    display: flex; align-items: center; justify-content: center;
    color: var(--primary); flex-shrink: 0;
}
.page-sub { font-size: 13px; color: var(--text-muted); margin: 0; }

/* ── Layout ── */
.profile-grid {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 20px;
    align-items: start;
}

/* ── Avatar Card ── */
.avatar-card {
    background: var(--surface-2); border: 1px solid var(--border-solid);
    border-radius: 16px; padding: 28px 20px;
    display: flex; flex-direction: column; align-items: center;
    gap: 10px; text-align: center; margin-bottom: 14px;
    position: relative; overflow: hidden;
}
.avatar-glow {
    position: absolute; top: -30px; left: 50%; transform: translateX(-50%);
    width: 100px; height: 100px; border-radius: 50%;
    background: radial-gradient(circle, rgba(35,169,189,0.15) 0%, transparent 70%);
    pointer-events: none;
}
.big-avatar {
    width: 76px; height: 76px; border-radius: 50%;
    background: linear-gradient(135deg, #23A9BD, #0D4E59);
    display: flex; align-items: center; justify-content: center;
    font-size: 30px; font-weight: 700; color: #fff;
    overflow: hidden; border: 3px solid rgba(35,169,189,0.3);
    box-shadow: 0 0 0 6px rgba(35,169,189,0.08);
}
.big-avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-info { display: flex; flex-direction: column; gap: 4px; }
.avatar-name { font-size: 16px; font-weight: 600; color: var(--text-primary); }
.avatar-email { font-size: 12px; color: var(--text-muted); }
.avatar-bio {
    font-size: 12.5px; color: var(--text-secondary); line-height: 1.65;
    margin-top: 4px;
}
.avatar-joined {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: var(--text-muted);
    background: rgba(255,255,255,0.03); border: 1px solid var(--border-solid);
    padding: 4px 10px; border-radius: 99px; margin-top: 4px;
}

/* ── Stats Card ── */
.stats-card {
    background: var(--surface-2); border: 1px solid var(--border-solid);
    border-radius: 14px; padding: 18px 14px;
    display: flex; justify-content: space-around; align-items: center; gap: 6px;
}
.stat-item { display: flex; align-items: center; gap: 10px; }
.stat-icon {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.stat-icon--total {
    background: rgba(35,169,189,0.1); color: var(--primary);
    border: 1px solid rgba(35,169,189,0.2);
}
.stat-icon--pinned {
    background: rgba(55,138,221,0.1); color: #378ADD;
    border: 1px solid rgba(55,138,221,0.2);
}
.stat-icon--archived {
    background: rgba(239,159,39,0.1); color: var(--warning);
    border: 1px solid rgba(239,159,39,0.2);
}
.stat-num { font-size: 20px; font-weight: 700; color: var(--text-primary); line-height: 1; }
.stat-num--pinned { color: #378ADD; }
.stat-num--archived { color: var(--warning); }
.stat-label { font-size: 11px; color: var(--text-muted); margin-top: 2px; }
.stats-divider { width: 1px; height: 28px; background: var(--border-solid); }

/* ── Form Card ── */
.form-card {
    background: var(--surface-2); border: 1px solid var(--border-solid);
    border-radius: 16px; padding: 28px;
}
.form-card-header { margin-bottom: 24px; }
.form-section-title { font-size: 16px; font-weight: 600; color: var(--text-primary); margin: 0 0 4px; }
.form-section-sub { font-size: 13px; color: var(--text-muted); margin: 0; }

.edit-form { display: flex; flex-direction: column; gap: 20px; }
.form-group { display: flex; flex-direction: column; gap: 7px; }
.form-label { font-size: 12.5px; font-weight: 600; color: var(--text-secondary); letter-spacing: 0.3px; }
.form-input {
    height: 42px; padding: 0 14px;
    background: rgba(0,0,0,0.25); border: 1px solid var(--border-solid); border-radius: 10px;
    color: var(--text-primary); font-size: 14px; outline: none;
    transition: border-color 0.18s, box-shadow 0.18s; font-family: inherit;
}
.form-input:focus {
    border-color: var(--primary-border);
    box-shadow: 0 0 0 3px rgba(35,169,189,0.08);
}
.form-input::placeholder { color: var(--text-muted); }
.form-textarea { height: auto; padding: 12px 14px; resize: vertical; line-height: 1.7; }
.input-error { border-color: rgba(226,75,74,0.5) !important; }
.form-error {
    display: flex; align-items: center; gap: 5px;
    font-size: 12px; color: var(--danger);
}

.current-avatar-preview {
    display: flex; align-items: center; gap: 10px;
    font-size: 12px; color: var(--text-muted);
    margin-bottom: 8px;
}
.current-avatar-preview img {
    width: 32px; height: 32px; border-radius: 50%;
    object-fit: cover; border: 1px solid var(--border-solid);
}

.file-input-wrap {
    display: flex; align-items: center; gap: 10px;
}
.file-input { position: absolute; opacity: 0; pointer-events: none; }
.file-label {
    display: inline-flex; align-items: center; gap: 6px;
    height: 36px; padding: 0 14px;
    background: rgba(35,169,189,0.08); border: 1px solid var(--primary-border);
    border-radius: 8px; color: var(--primary); font-size: 13px; font-weight: 500;
    cursor: pointer; transition: all 0.18s; font-family: inherit; white-space: nowrap;
}
.file-label:hover { background: rgba(35,169,189,0.15); }
.file-name { font-size: 12.5px; color: var(--text-muted); }

.form-separator {
    height: 1px; background: var(--border-solid); margin: 4px 0;
}
.form-actions {
    display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;
}
.btn-secondary {
    height: 40px; padding: 0 18px;
    background: rgba(255,255,255,0.04); border: 1px solid var(--border-solid);
    border-radius: 9px; color: var(--text-secondary); font-size: 13.5px;
    cursor: pointer; transition: all 0.18s; font-family: inherit;
}
.btn-secondary:hover { border-color: #2e4a60; color: var(--text-primary); }
.btn-primary {
    display: flex; align-items: center; gap: 7px;
    height: 40px; padding: 0 22px;
    background: var(--primary); border: 1px solid var(--primary);
    border-radius: 9px; color: #0a1822; font-size: 13.5px; font-weight: 700;
    cursor: pointer; transition: all 0.18s; font-family: inherit;
}
.btn-primary:hover {
    background: #2bbdd4; border-color: #2bbdd4;
    box-shadow: 0 4px 16px rgba(35,169,189,0.3);
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    .stats-card {
        justify-content: space-between;
    }
    .form-card { padding: 20px 16px; }
    .avatar-card { padding: 20px 16px; }
}
@media (max-width: 480px) {
    .form-actions { flex-direction: column-reverse; }
    .btn-primary, .btn-secondary { width: 100%; justify-content: center; }
    .stat-item { flex-direction: column; text-align: center; gap: 6px; }
    .stats-card { padding: 16px 8px; }
}
</style>

@endsection