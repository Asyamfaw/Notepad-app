@extends('layouts.app')
@section('title', 'Profil — Notes App')
@section('content')
@php $user = auth()->user(); @endphp

<div class="profile-page">
    <div class="page-header">
        <h1 class="page-title">Profil</h1>
        <p class="page-sub">Kelola informasi akunmu</p>
    </div>

    @if(session('success'))
        <div class="flash-success">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-grid">

        {{-- ── Stats Card ── --}}
        <div class="stats-col">
            <div class="avatar-card">
                <div class="big-avatar">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" alt="avatar">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <div class="avatar-name">{{ $user->name }}</div>
                <div class="avatar-email">{{ $user->email }}</div>
                @if($user->bio)
                    <p class="avatar-bio">{{ $user->bio }}</p>
                @endif
            </div>

            <div class="stats-card">
                <div class="stat-item">
                    <span class="stat-num">{{ $stats['total_notes'] }}</span>
                    <span class="stat-label">Total Catatan</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-num" style="color:#378ADD">{{ $stats['pinned_notes'] }}</span>
                    <span class="stat-label">Dipin</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-num" style="color:#EF9F27">{{ $stats['archived_notes'] }}</span>
                    <span class="stat-label">Diarsipkan</span>
                </div>
            </div>
        </div>

        {{-- ── Edit Form ── --}}
        <div class="form-col">
            <div class="form-card">
                <h2 class="form-section-title">Edit Profil</h2>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="edit-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="form-input {{ $errors->has('name') ? 'input-error' : '' }}"
                               placeholder="Nama lengkap">
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="3"
                                  class="form-input form-textarea"
                                  placeholder="Ceritakan sedikit tentang dirimu...">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Foto Profil</label>
                        <input type="file" name="avatar" accept="image/*"
                               class="form-input" style="height:auto;padding:8px 12px;">
                        @error('avatar') <span class="form-error">{{ $message }}</span> @enderror
                        @if($user->avatar)
                            <p style="font-size:11px;color:#555;margin-top:4px;">Foto saat ini sudah ada. Upload baru untuk mengganti.</p>
                        @endif
                    </div>

                    <div style="display:flex;justify-content:flex-end;margin-top:8px;">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.profile-page { font-family: 'Inter', sans-serif; }
.page-header { margin-bottom: 20px; }
.page-title { font-size: 22px; font-weight: 500; color: #e8e6e0; letter-spacing: -0.3px; }
.page-sub { font-size: 13px; color: #555; margin-top: 2px; }
.flash-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(151,196,89,0.1); border: 1px solid rgba(151,196,89,0.25);
    color: #97C459; font-size: 13px; padding: 10px 14px;
    border-radius: 8px; margin-bottom: 20px;
}

.profile-grid { display: grid; grid-template-columns: 260px 1fr; gap: 20px; }

.avatar-card {
    background: #111; border: 1px solid #1e1e1e; border-radius: 12px; padding: 24px;
    display: flex; flex-direction: column; align-items: center; gap: 8px; text-align: center;
    margin-bottom: 12px;
}
.big-avatar {
    width: 72px; height: 72px; border-radius: 50%;
    background: linear-gradient(135deg, #378ADD, #5BA4E6);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; font-weight: 600; color: #fff;
    overflow: hidden; margin-bottom: 4px;
}
.big-avatar img { width: 100%; height: 100%; object-fit: cover; }
.avatar-name { font-size: 15px; font-weight: 500; color: #e8e6e0; }
.avatar-email { font-size: 12px; color: #555; }
.avatar-bio { font-size: 12px; color: #666; line-height: 1.6; margin-top: 4px; }

.stats-card {
    background: #111; border: 1px solid #1e1e1e; border-radius: 12px; padding: 16px;
    display: flex; justify-content: space-around; align-items: center; gap: 8px;
}
.stat-item { display: flex; flex-direction: column; align-items: center; gap: 4px; }
.stat-num { font-size: 22px; font-weight: 600; color: #e8e6e0; }
.stat-label { font-size: 11px; color: #555; text-align: center; }
.stat-divider { width: 1px; height: 32px; background: #1e1e1e; }

.form-card {
    background: #111; border: 1px solid #1e1e1e; border-radius: 12px; padding: 24px;
}
.form-section-title { font-size: 15px; font-weight: 500; color: #c8c6c0; margin-bottom: 20px; }

.edit-form { display: flex; flex-direction: column; gap: 16px; }
.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label { font-size: 12.5px; font-weight: 500; color: #888; }
.form-input {
    height: 40px; padding: 0 12px;
    background: #0a0a0a; border: 1px solid #222; border-radius: 8px;
    color: #e0e0e0; font-size: 14px; outline: none;
    transition: border-color 0.15s; font-family: inherit; width: 100%;
}
.form-input:focus { border-color: #2e2e2e; }
.form-textarea { height: auto; padding: 10px 12px; resize: vertical; line-height: 1.7; }
.input-error { border-color: #E24B4A !important; }
.form-error { font-size: 12px; color: #E24B4A; }
.btn-primary {
    height: 40px; padding: 0 22px;
    background: #378ADD; border: none; border-radius: 8px;
    color: #fff; font-size: 13.5px; font-weight: 500;
    cursor: pointer; transition: background 0.15s; font-family: inherit;
}
.btn-primary:hover { background: #2e72c9; }
</style>
@endsection
