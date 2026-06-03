<div class="auth-form">
    <h1 class="auth-title">Masuk</h1>
    <p class="auth-sub">Selamat datang kembali 👋</p>

    <form wire:submit.prevent="login">
        <div class="form-group">
            <label class="form-label">Email</label>
            <input
                type="email"
                wire:model.defer="email"
                class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                placeholder="email@example.com"
                autofocus
            >
            @error('email')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">Password</label>
            <input
                type="password"
                wire:model.defer="password"
                class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                placeholder="••••••••"
            >
            @error('password')
                <span class="form-error">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-check">
            <input type="checkbox" wire:model.defer="remember" id="remember">
            <label for="remember">Ingat saya</label>
        </div>

        <button type="submit" class="btn-primary" wire:loading.attr="disabled">
            <span wire:loading.remove>Masuk</span>
            <span wire:loading>Memproses...</span>
        </button>
    </form>

    <p class="auth-footer">
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar sekarang</a>
    </p>
</div>

<style>
.auth-form { display: flex; flex-direction: column; gap: 4px; }
.auth-title { font-size: 22px; font-weight: 600; color: #e8e6e0; margin-bottom: 4px; }
.auth-sub { font-size: 13px; color: #666; margin-bottom: 24px; }

.form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
.form-label { font-size: 12.5px; font-weight: 500; color: #999; }
.form-input {
    height: 40px; padding: 0 12px;
    background: #0a0a0a; border: 1px solid #2a2a2a;
    border-radius: 8px; color: #e8e6e0;
    font-size: 14px; outline: none;
    transition: border-color 0.15s; font-family: inherit;
}
.form-input:focus { border-color: #378ADD; }
.form-input::placeholder { color: #444; }
.input-error { border-color: #E24B4A !important; }
.form-error { font-size: 12px; color: #E24B4A; }

.form-check { display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
.form-check input { accent-color: #378ADD; cursor: pointer; }
.form-check label { font-size: 13px; color: #888; cursor: pointer; }

.btn-primary {
    width: 100%; height: 42px;
    background: #378ADD; border: none;
    border-radius: 10px; color: #fff;
    font-size: 14px; font-weight: 500;
    cursor: pointer; transition: background 0.15s;
    font-family: inherit;
}
.btn-primary:hover { background: #2e72c9; }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }

.auth-footer { font-size: 13px; color: #555; text-align: center; margin-top: 20px; }
.auth-footer a { color: #378ADD; text-decoration: none; }
.auth-footer a:hover { text-decoration: underline; }
</style>
