<div class="trash-page">

    {{-- ── Flash Message ── --}}
    @if (session()->has('success'))
        <div class="flash-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Search Bar + Filter ── --}}
    <div class="search-bar">
        <div class="search-wrap">
            <svg class="search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Cari di sampah..."
                class="search-input"
            >
        </div>

        <select wire:model="category" class="filter-select">
            <option value="">Semua kategori</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        {{-- Tombol Empty Trash --}}
        @if ($notes->isNotEmpty())
            <button wire:click="$set('showConfirmEmpty', true)" class="btn-empty-trash">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
                Kosongkan Sampah
            </button>
        @endif
    </div>

    {{-- ── Warning Banner ── --}}
    <div class="warning-banner" x-data="{ show: true }" x-show="show">
        <div class="warning-inner">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <span>
                Item di sampah akan <strong>dihapus permanen setelah 30 hari</strong>.
                Catatan yang dihapus tetap tersimpan sementara. Pulihkan kapan saja sebelum kedaluwarsa.
            </span>
        </div>
        <button class="warning-close" @click="show = false">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- ── Heading ── --}}
    <div class="page-heading">
        <div>
            <h1 class="page-title">Sampah</h1>
            <p class="page-sub">
                {{ $notes->count() }} catatan
                @if ($notes->count() > 0) · akan dihapus permanen otomatis setelah 30 hari @endif
            </p>
        </div>
    </div>

    {{-- ── Empty State ── --}}
    @if ($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/>
                </svg>
            </div>
            <p class="empty-title">Sampah kosong</p>
            <p class="empty-sub">Catatan yang kamu hapus akan muncul di sini.</p>
        </div>

    {{-- ── Note Cards ── --}}
    @else
        <div class="notes-grid">
            @foreach ($notes as $note)
                @php
                    $daysAgo  = $note->deleted_at->diffInDays(now());
                    $daysLeft = 30 - $daysAgo;
                    $isUrgent = $daysLeft <= 7;
                @endphp

                <div class="note-card {{ $isUrgent ? 'note-card--urgent' : '' }}" wire:key="note-{{ $note->id }}">

                    {{-- Deleted badge --}}
                    <div class="card-top">
                        <span class="deleted-badge {{ $isUrgent ? 'deleted-badge--urgent' : '' }}">
                            DIHAPUS {{ $daysAgo == 0 ? 'HARI INI' : $daysAgo . ' HARI LALU' }}
                        </span>
                        @if ($isUrgent)
                            <span class="expire-badge">{{ $daysLeft }} hari tersisa</span>
                        @endif
                    </div>

                    {{-- Judul --}}
                    <h3 class="note-title">{{ $note->title }}</h3>

                    {{-- Excerpt --}}
                    <p class="note-excerpt">{{ Str::limit($note->content, 120) }}</p>

                    {{-- Category badge --}}
                    @if ($note->category)
                        <span class="category-badge" style="--cat-color: {{ $note->category->color ?? '#888' }}">
                            {{ $note->category->name }}
                        </span>
                    @endif

                    {{-- Actions --}}
                    <div class="card-actions">
                        <button
                            wire:click="restore({{ $note->id }})"
                            wire:loading.attr="disabled"
                            wire:target="restore({{ $note->id }})"
                            class="btn-restore"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                            </svg>
                            <span wire:loading.remove wire:target="restore({{ $note->id }})">Restore</span>
                            <span wire:loading wire:target="restore({{ $note->id }})">Memulihkan...</span>
                        </button>

                        <button
                            wire:click="forceDelete({{ $note->id }})"
                            wire:loading.attr="disabled"
                            wire:target="forceDelete({{ $note->id }})"
                            wire:confirm="Hapus permanen? Tindakan ini tidak dapat dibatalkan."
                            class="btn-delete"
                        >
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                            </svg>
                            <span wire:loading.remove wire:target="forceDelete({{ $note->id }})">Hapus Permanen</span>
                            <span wire:loading wire:target="forceDelete({{ $note->id }})">Menghapus...</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- ── Modal Konfirmasi Empty Trash ── --}}
    @if ($showConfirmEmpty)
        <div class="modal-overlay" wire:click.self="$set('showConfirmEmpty', false)">
            <div class="modal">
                <div class="modal-icon">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <h2 class="modal-title">Kosongkan Sampah?</h2>
                <p class="modal-desc">
                    Semua <strong>{{ $notes->count() }} catatan</strong> akan dihapus secara permanen.
                    Tindakan ini <strong>tidak dapat dibatalkan</strong>.
                </p>
                <div class="modal-actions">
                    <button wire:click="$set('showConfirmEmpty', false)" class="btn-cancel">Batal</button>
                    <button wire:click="emptyTrash" wire:loading.attr="disabled" class="btn-confirm-delete">
                        <span wire:loading.remove wire:target="emptyTrash">Ya, Hapus Semua</span>
                        <span wire:loading wire:target="emptyTrash">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

{{-- ── Styles ── --}}
<style>
.trash-page {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 24px 48px;
    font-family: 'Instrument Sans', 'Inter', sans-serif;
}

/* Flash */
.flash-success {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(99, 153, 34, 0.12);
    border: 1px solid rgba(99, 153, 34, 0.3);
    color: #97C459;
    font-size: 13px;
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 16px;
}

/* Search Bar */
.search-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 0;
    margin-bottom: 12px;
}
.search-wrap {
    flex: 1;
    position: relative;
    display: flex;
    align-items: center;
}
.search-icon {
    position: absolute;
    left: 12px;
    color: #666;
    pointer-events: none;
}
.search-input {
    width: 100%;
    height: 36px;
    padding: 0 12px 0 36px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    color: #e0e0e0;
    font-size: 13px;
    outline: none;
    transition: border-color 0.15s;
}
.search-input:focus { border-color: #444; }
.search-input::placeholder { color: #555; }

.filter-select {
    height: 36px;
    padding: 0 12px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    border-radius: 8px;
    color: #999;
    font-size: 13px;
    cursor: pointer;
    outline: none;
}

.btn-empty-trash {
    display: flex;
    align-items: center;
    gap: 6px;
    height: 36px;
    padding: 0 14px;
    background: rgba(226, 75, 74, 0.1);
    border: 1px solid rgba(226, 75, 74, 0.3);
    border-radius: 8px;
    color: #E24B4A;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s;
    white-space: nowrap;
}
.btn-empty-trash:hover {
    background: rgba(226, 75, 74, 0.18);
    border-color: rgba(226, 75, 74, 0.5);
}

/* Warning Banner */
.warning-banner {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 12px;
    background: rgba(186, 117, 23, 0.08);
    border: 1px solid rgba(186, 117, 23, 0.25);
    border-radius: 10px;
    padding: 12px 16px;
    margin-bottom: 24px;
}
.warning-inner {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    color: #BA7517;
    font-size: 13px;
    line-height: 1.6;
}
.warning-inner svg { flex-shrink: 0; margin-top: 2px; }
.warning-inner strong { color: #EF9F27; }
.warning-close {
    background: none;
    border: none;
    color: #666;
    cursor: pointer;
    padding: 2px;
    flex-shrink: 0;
    transition: color 0.15s;
}
.warning-close:hover { color: #999; }

/* Heading */
.page-heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
}
.page-title {
    font-size: 22px;
    font-weight: 500;
    color: #e8e6e0;
    margin: 0 0 4px;
    letter-spacing: -0.3px;
}
.page-sub {
    font-size: 13px;
    color: #555;
    margin: 0;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 0;
    gap: 12px;
}
.empty-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #444;
    margin-bottom: 4px;
}
.empty-title { font-size: 15px; font-weight: 500; color: #666; margin: 0; }
.empty-sub   { font-size: 13px; color: #444; margin: 0; }

/* Notes Grid */
.notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 16px;
}

/* Note Card */
.note-card {
    background: #111;
    border: 1px solid #222;
    border-radius: 12px;
    padding: 18px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: border-color 0.15s;
}
.note-card:hover { border-color: #333; }
.note-card--urgent {
    border-color: rgba(226, 75, 74, 0.25);
    background: rgba(226, 75, 74, 0.03);
}
.note-card--urgent:hover { border-color: rgba(226, 75, 74, 0.4); }

.card-top {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.deleted-badge {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.8px;
    color: #555;
    background: #1a1a1a;
    border: 1px solid #2a2a2a;
    padding: 3px 8px;
    border-radius: 99px;
}
.deleted-badge--urgent {
    color: #E24B4A;
    background: rgba(226, 75, 74, 0.1);
    border-color: rgba(226, 75, 74, 0.25);
}
.expire-badge {
    font-size: 10px;
    font-weight: 500;
    color: #EF9F27;
    background: rgba(239, 159, 39, 0.1);
    border: 1px solid rgba(239, 159, 39, 0.25);
    padding: 3px 8px;
    border-radius: 99px;
    letter-spacing: 0.5px;
}

.note-title {
    font-size: 15px;
    font-weight: 500;
    color: #c8c6c0;
    margin: 0;
    line-height: 1.4;
}
.note-excerpt {
    font-size: 13px;
    color: #555;
    line-height: 1.7;
    margin: 0;
    flex: 1;
}
.category-badge {
    display: inline-flex;
    align-items: center;
    font-size: 11px;
    padding: 3px 9px;
    border-radius: 99px;
    background: color-mix(in srgb, var(--cat-color) 15%, transparent);
    border: 1px solid color-mix(in srgb, var(--cat-color) 30%, transparent);
    color: var(--cat-color);
    width: fit-content;
}

/* Card Actions */
.card-actions {
    display: flex;
    gap: 8px;
    margin-top: 4px;
    padding-top: 12px;
    border-top: 1px solid #1e1e1e;
}
.btn-restore, .btn-delete {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    height: 32px;
    border-radius: 7px;
    font-size: 12px;
    cursor: pointer;
    border: 1px solid;
    transition: all 0.15s;
}
.btn-restore {
    background: rgba(55, 138, 221, 0.08);
    border-color: rgba(55, 138, 221, 0.25);
    color: #378ADD;
}
.btn-restore:hover {
    background: rgba(55, 138, 221, 0.15);
    border-color: rgba(55, 138, 221, 0.4);
}
.btn-delete {
    background: rgba(226, 75, 74, 0.08);
    border-color: rgba(226, 75, 74, 0.2);
    color: #E24B4A;
}
.btn-delete:hover {
    background: rgba(226, 75, 74, 0.15);
    border-color: rgba(226, 75, 74, 0.35);
}
.btn-restore:disabled, .btn-delete:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* Modal */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    backdrop-filter: blur(4px);
}
.modal {
    background: #141414;
    border: 1px solid #2a2a2a;
    border-radius: 16px;
    padding: 32px;
    max-width: 400px;
    width: 90%;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    text-align: center;
}
.modal-icon {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    background: rgba(226, 75, 74, 0.1);
    border: 1px solid rgba(226, 75, 74, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #E24B4A;
    margin-bottom: 4px;
}
.modal-title {
    font-size: 18px;
    font-weight: 500;
    color: #e0ddd8;
    margin: 0;
}
.modal-desc {
    font-size: 13px;
    color: #666;
    line-height: 1.7;
    margin: 0;
}
.modal-desc strong { color: #999; }
.modal-actions {
    display: flex;
    gap: 10px;
    margin-top: 8px;
    width: 100%;
}
.btn-cancel {
    flex: 1;
    height: 38px;
    background: #1e1e1e;
    border: 1px solid #2e2e2e;
    border-radius: 8px;
    color: #888;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-cancel:hover { border-color: #3e3e3e; color: #aaa; }
.btn-confirm-delete {
    flex: 1;
    height: 38px;
    background: #E24B4A;
    border: 1px solid #E24B4A;
    border-radius: 8px;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}
.btn-confirm-delete:hover { background: #c83d3c; }
.btn-confirm-delete:disabled { opacity: 0.6; cursor: not-allowed; }
</style>