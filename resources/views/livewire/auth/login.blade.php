@extends('layouts.auth')

@section('title', 'Sign In — Noteku')

@section('logo-sub')
    <p class="auth-logo-sub">Atmospheric Obsidian Workspace</p>
@endsection

@section('content')
<div class="auth-form">

    <form wire:submit.prevent="login">

        {{-- Email --}}
        <div class="form-group">
            <label class="form-label">Email Address</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="4" width="20" height="16" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </span>
                <input
                    type="email"
                    wire:model.defer="email"
                    class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                    placeholder="name@company.com"
                    autofocus
                >
            </div>
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Password --}}
        <div class="form-group">
            <div class="label-row">
                <label class="form-label">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                @endif
            </div>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input
                    type="password"
                    wire:model.defer="password"
                    id="pw-field"
                    class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                    placeholder="••••••••"
                >
                <button type="button" class="input-toggle" onclick="togglePw()" aria-label="Toggle password">
                    <svg id="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        {{-- Remember --}}
        <div class="form-check">
            <input type="checkbox" wire:model.defer="remember" id="remember">
            <label for="remember">Remember for 30 days</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove>Sign In</span>
            <span wire:loading>Processing...</span>
        </button>
    </form>

    {{-- Divider --}}
    <div class="divider">
        <span>OR CONTINUE WITH</span>
    </div>


    {{-- Footer link --}}
    <p class="auth-footer">
        Don't have an account?
        <a href="{{ route('register') }}">Create Account</a>
    </p>
</div>

<style>
.auth-form { display: flex; flex-direction: column; gap: 0; }

.form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }

.label-row { display: flex; align-items: center; justify-content: space-between; }
.form-label { font-size: 12.5px; font-weight: 500; color: #6b8fa3; }
.forgot-link { font-size: 12.5px; color: #3a5a6e; text-decoration: none; transition: color 0.15s; }
.forgot-link:hover { color: #6b8fa3; }

.input-wrap { position: relative; display: flex; align-items: center; }

.input-icon {
    position: absolute;
    left: 12px;
    display: flex;
    align-items: center;
    pointer-events: none;
}
.input-icon svg { width: 15px; height: 15px; stroke: #3a5a6e; }

.form-input {
    width: 100%;
    height: 44px;
    padding: 0 42px 0 40px;
    background: #0c1c28;
    border: 1px solid #163040;
    border-radius: 10px;
    color: #e2eaf0;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    outline: none;
    transition: border-color 0.15s, box-shadow 0.15s;
}
.form-input:focus {
    border-color: rgba(35,169,189,0.5);
    box-shadow: 0 0 0 3px rgba(35,169,189,0.1);
}
.form-input::placeholder { color: #3a5a6e; }
.input-error { border-color: #E24B4A !important; }
.form-error { font-size: 12px; color: #E24B4A; }

.input-toggle {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 0;
}
.input-toggle svg { width: 15px; height: 15px; stroke: #3a5a6e; transition: stroke 0.15s; }
.input-toggle:hover svg { stroke: #6b8fa3; }

.form-check { display: flex; align-items: center; gap: 8px; margin-bottom: 18px; }
.form-check input { accent-color: #23A9BD; cursor: pointer; width: 14px; height: 14px; }
.form-check label { font-size: 13px; color: #6b8fa3; cursor: pointer; }

.btn-primary {
    width: 100%;
    height: 46px;
    background: #23A9BD;
    border: none;
    border-radius: 10px;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s, box-shadow 0.15s;
    font-family: 'Hanken Grotesk', sans-serif;
    letter-spacing: 0.1px;
    box-shadow: 0 0 20px rgba(35,169,189,0.3);
}
.btn-primary:hover {
    background: #1b8a9a;
    box-shadow: 0 0 28px rgba(35,169,189,0.4);
}
.btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }

.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 20px 0;
}
.divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #163040;
}
.divider span {
    font-size: 10.5px;
    color: #3a5a6e;
    letter-spacing: 0.8px;
    white-space: nowrap;
}

.btn-google {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    height: 44px;
    background: #0c1c28;
    border: 1px solid #1e3f53;
    border-radius: 10px;
    color: #e2eaf0;
    font-size: 14px;
    font-weight: 400;
    text-decoration: none;
    font-family: 'Inter', sans-serif;
    transition: background 0.15s, border-color 0.15s;
}
.btn-google:hover { background: #112030; border-color: #2a5570; }

.auth-footer { font-size: 13px; color: #3a5a6e; text-align: center; margin-top: 22px; }
.auth-footer a { color: #23A9BD; text-decoration: underline; text-underline-offset: 3px; }
.auth-footer a:hover { color: #7dd8e6; }
</style>

<script>
function togglePw() {
    const f = document.getElementById('pw-field');
    f.type = f.type === 'password' ? 'text' : 'password';
}
</script>
@endsection