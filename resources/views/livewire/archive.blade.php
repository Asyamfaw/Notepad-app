<div class="archive-page">

    {{-- ── Flash ── --}}
    @if(session()->has('success'))
        <div class="flash-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3500)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="page-heading">
        <div>
            <h1 class="page-title">Arsip</h1>
            <p class="page-sub">{{ $notes->count() }} catatan diarsipkan</p>
        </div>
    </div>

    {{-- ── Filter ── --}}
    <div class="filter-bar">
        <div class="search-wrap">
            <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" wire:model.debounce.300ms="search" placeholder="Cari di arsip..." class="search-input">
        </div>
        <select wire:model="category" class="filter-select">
            <option value="">Semua kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- ── Empty ── --}}
    @if($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/>
                    <line x1="10" y1="12" x2="14" y2="12"/>
                </svg>
            </div>
            <p class="empty-title">Arsip kosong</p>
            <p class="empty-sub">Catatan yang kamu arsipkan akan muncul di sini.</p>
        </div>
    @else
        <div class="notes-grid">
            @foreach($notes as $note)
                <div class="note-card" wire:key="note-{{ $note->id }}"
                     @if($note->color_label) style="border-top:2px solid {{ $note->color_label }}" @endif>

                    {{-- Badges --}}
                    <div class="card-badges">
                        <span class="badge-archived">📦 ARSIP</span>
                        @php $pColor = ['high'=>'#E24B4A','medium'=>'#EF9F27','low'=>'#97C459']; @endphp
                        <span class="badge-priority" style="color:{{ $pColor[$note->priority]??'#999' }}">
                            {{ ucfirst($note->priority) }}
                        </span>
                    </div>

                    <h3 class="note-title">{{ $note->title }}</h3>
                    <p class="note-excerpt">{{ Str::limit($note->content, 100) }}</p>

                    <div class="note-meta">
                        @if($note->category)
                            <span class="meta-badge" style="--c:{{ $note->category->color ?? '#888' }}">{{ $note->category->name }}</span>
                        @endif
                        @foreach($note->tags->take(3) as $tag)
                            <span class="tag-badge">#{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    <div class="card-actions">
                        <button wire:click="unarchive({{ $note->id }})" class="btn-restore"
                                wire:loading.attr="disabled" wire:target="unarchive({{ $note->id }})">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.74 2.74L3 8"/><path d="M3 3v5h5"/>
                            </svg>
                            <span wire:loading.remove wire:target="unarchive({{ $note->id }})">Pulihkan</span>
                            <span wire:loading wire:target="unarchive({{ $note->id }})">...</span>
                        </button>
                        <button wire:click="delete({{ $note->id }})" class="btn-delete"
                                wire:confirm="Hapus ke sampah?"
                                wire:loading.attr="disabled" wire:target="delete({{ $note->id }})">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.archive-page { font-family: 'Inter', sans-serif; }
.page-heading { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.page-title { font-size: 22px; font-weight: 500; color: #e8e6e0; letter-spacing: -0.3px; }
.page-sub { font-size: 13px; color: #555; margin-top: 2px; }

.flash-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(151,196,89,0.1); border: 1px solid rgba(151,196,89,0.25);
    color: #97C459; font-size: 13px; padding: 10px 14px;
    border-radius: 8px; margin-bottom: 16px;
}

.filter-bar { display: flex; gap: 10px; margin-bottom: 20px; }
.search-wrap { flex: 1; position: relative; display: flex; align-items: center; }
.search-icon { position: absolute; left: 11px; color: #555; pointer-events: none; }
.search-input {
    width: 100%; height: 36px; padding: 0 12px 0 34px;
    background: #111; border: 1px solid #222; border-radius: 8px;
    color: #e0e0e0; font-size: 13px; outline: none; font-family: inherit;
}
.filter-select {
    height: 36px; padding: 0 12px;
    background: #111; border: 1px solid #222; border-radius: 8px;
    color: #888; font-size: 13px; cursor: pointer; outline: none; font-family: inherit;
}

.empty-state {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center; padding: 80px 0; gap: 10px;
}
.empty-icon {
    width: 60px; height: 60px; border-radius: 14px;
    background: #111; border: 1px solid #222;
    display: flex; align-items: center; justify-content: center; color: #333; margin-bottom: 4px;
}
.empty-title { font-size: 15px; font-weight: 500; color: #555; }
.empty-sub { font-size: 13px; color: #3a3a3a; }

.notes-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 14px;
}
.note-card {
    background: #111; border: 1px solid #1e1e1e; border-radius: 12px; padding: 16px;
    display: flex; flex-direction: column; gap: 8px; transition: border-color 0.15s;
}
.note-card:hover { border-color: #2a2a2a; }

.card-badges { display: flex; gap: 6px; align-items: center; }
.badge-archived {
    font-size: 10px; font-weight: 500; letter-spacing: 0.5px;
    padding: 3px 8px; border-radius: 99px;
    background: rgba(239,159,39,0.08); border: 1px solid rgba(239,159,39,0.2);
    color: #EF9F27;
}
.badge-priority { font-size: 11px; color: #555; }

.note-title { font-size: 14.5px; font-weight: 500; color: #c8c6c0; line-height: 1.4; }
.note-excerpt { font-size: 12.5px; color: #505050; line-height: 1.7; flex: 1; }
.note-meta { display: flex; gap: 6px; flex-wrap: wrap; }
.meta-badge {
    font-size: 11px; padding: 2px 8px; border-radius: 99px;
    background: color-mix(in srgb, var(--c) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--c) 25%, transparent);
    color: var(--c);
}
.tag-badge { font-size: 11px; color: #555; }

.card-actions {
    display: flex; gap: 8px; padding-top: 10px;
    margin-top: 2px; border-top: 1px solid #1a1a1a;
}
.btn-restore, .btn-delete {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    height: 30px; border-radius: 7px; font-size: 12px;
    cursor: pointer; border: 1px solid; transition: all 0.15s; font-family: inherit; background: none;
}
.btn-restore { color: #378ADD; border-color: rgba(55,138,221,0.25); }
.btn-restore:hover { background: rgba(55,138,221,0.08); }
.btn-delete { color: #E24B4A; border-color: rgba(226,75,74,0.2); }
.btn-delete:hover { background: rgba(226,75,74,0.08); }
.btn-restore:disabled, .btn-delete:disabled { opacity: 0.5; cursor: not-allowed; }
</style>
