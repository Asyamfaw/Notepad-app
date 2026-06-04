<div class="archive-page">

    {{-- ── Flash ── --}}
    @if(session()->has('success'))
        <div class="flash-success"
             x-data="{ show: true }" x-show="show"
             x-init="setTimeout(() => show = false, 3500)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header ── --}}
    <div class="page-heading">
        <div>
            <h1 class="page-title">
                <span class="title-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="21 8 21 21 3 21 3 8"/>
                        <rect x="1" y="3" width="22" height="5"/>
                        <line x1="10" y1="12" x2="14" y2="12"/>
                    </svg>
                </span>
                Arsip
            </h1>
            <p class="page-sub">
                <span class="count-badge">{{ $notes->count() }}</span>
                catatan diarsipkan
            </p>
        </div>
        <button class="btn-empty-archive" wire:click="emptyArchive"
                wire:confirm="Kosongkan semua arsip?"
                @if($notes->isEmpty()) style="display:none" @endif>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
            </svg>
            Empty Archive
        </button>
    </div>

    {{-- ── Filter ── --}}
    <div class="filter-bar">
        <div class="search-wrap">
            <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" wire:model.debounce.300ms="search"
                   placeholder="Search archive..." class="search-input">
        </div>
        <select wire:model="category" class="filter-select">
            <option value="">All categories</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- ── Empty ── --}}
    @if($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="21 8 21 21 3 21 3 8"/>
                    <rect x="1" y="3" width="22" height="5"/>
                    <line x1="10" y1="12" x2="14" y2="12"/>
                </svg>
            </div>
            <p class="empty-title">Archive is empty</p>
            <p class="empty-sub">Notes you archive will appear here.</p>
        </div>
    @else
        <div class="notes-grid">
            @foreach($notes as $note)
                <div class="note-card" wire:key="note-{{ $note->id }}"
                     @if($note->color_label) style="--card-accent: {{ $note->color_label }}" @else style="--card-accent: #23A9BD" @endif>

                    <div class="card-accent-bar"></div>

                    {{-- Badges --}}
                    <div class="card-badges">
                        <span class="badge-archived">
                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="21 8 21 21 3 21 3 8"/>
                                <rect x="1" y="3" width="22" height="5"/>
                            </svg>
                            ARSIP
                        </span>
                        @php $pColor = ['high'=>'#E24B4A','medium'=>'#EF9F27','low'=>'#23A9BD']; @endphp
                        <span class="badge-priority" style="color:{{ $pColor[$note->priority]??'#999' }}; border-color: {{ $pColor[$note->priority]??'#333' }}40">
                            {{ ucfirst($note->priority) }}
                        </span>
                    </div>

                    <h3 class="note-title">{{ $note->title }}</h3>
                    <p class="note-excerpt">{{ Str::limit($note->content, 110) }}</p>

                    <div class="note-meta">
                        @if($note->category)
                            <span class="meta-badge" style="--c:{{ $note->category->color ?? '#23A9BD' }}">
                                {{ $note->category->name }}
                            </span>
                        @endif
                        @foreach($note->tags->take(3) as $tag)
                            <span class="tag-badge">#{{ $tag->name }}</span>
                        @endforeach
                    </div>

                    @if($note->archived_at)
                        <div class="note-date">
                            <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                            </svg>
                            Diarsipkan {{ $note->archived_at->diffForHumans() }}
                        </div>
                    @endif

                    <div class="card-actions">
                        <button wire:click="unarchive({{ $note->id }})" class="btn-restore"
                                wire:loading.attr="disabled" wire:target="unarchive({{ $note->id }})">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                            </svg>
                            <span wire:loading.remove wire:target="unarchive({{ $note->id }})">Pulihkan</span>
                            <span wire:loading wire:target="unarchive({{ $note->id }})">...</span>
                        </button>
                        <button wire:click="delete({{ $note->id }})" class="btn-delete"
                                wire:confirm="Hapus ke sampah?"
                                wire:loading.attr="disabled" wire:target="delete({{ $note->id }})">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14H6L5 6"/>
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
@import url('https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700&display=swap');

.archive-page {
    font-family: 'Hanken Grotesk', 'Inter', sans-serif;
    --primary: #23A9BD;
    --primary-dim: rgba(35, 169, 189, 0.12);
    --primary-border: rgba(35, 169, 189, 0.25);
    --secondary: #0D4E59;
    --tertiary: #DA8642;
    --surface: #0d1117;
    --surface-2: #131b24;
    --surface-3: #1a2535;
    --border: rgba(35, 169, 189, 0.1);
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
    border-radius: 10px; margin-bottom: 20px;
    backdrop-filter: blur(8px);
}

.page-heading {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 20px; flex-wrap: wrap; gap: 12px;
}
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
.page-sub {
    font-size: 13px; color: var(--text-muted);
    display: flex; align-items: center; gap: 6px;
}
.count-badge {
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--primary-dim); border: 1px solid var(--primary-border);
    color: var(--primary); font-size: 11px; font-weight: 600;
    padding: 1px 7px; border-radius: 99px;
}
.btn-empty-archive {
    display: flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 14px;
    background: rgba(226,75,74,0.06); border: 1px solid rgba(226,75,74,0.2);
    border-radius: 8px; color: #E24B4A; font-size: 12.5px;
    cursor: pointer; transition: all 0.18s; white-space: nowrap;
    font-family: inherit;
}
.btn-empty-archive:hover {
    background: rgba(226,75,74,0.12); border-color: rgba(226,75,74,0.4);
}

.filter-bar {
    display: flex; gap: 10px; margin-bottom: 24px; flex-wrap: wrap;
}
.search-wrap {
    flex: 1; min-width: 200px; position: relative;
    display: flex; align-items: center;
}
.search-icon {
    position: absolute; left: 12px; color: var(--text-muted); pointer-events: none;
}
.search-input {
    width: 100%; height: 38px; padding: 0 12px 0 36px;
    background: var(--surface-2); border: 1px solid var(--border-solid); border-radius: 9px;
    color: var(--text-primary); font-size: 13px; outline: none;
    font-family: inherit; transition: border-color 0.18s;
}
.search-input:focus { border-color: var(--primary-border); box-shadow: 0 0 0 3px rgba(35,169,189,0.06); }
.search-input::placeholder { color: var(--text-muted); }

.filter-select {
    height: 38px; padding: 0 12px;
    background: var(--surface-2); border: 1px solid var(--border-solid); border-radius: 9px;
    color: var(--text-secondary); font-size: 13px; cursor: pointer; outline: none;
    font-family: inherit; transition: border-color 0.18s;
}
.filter-select:focus { border-color: var(--primary-border); }

.empty-state {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 80px 0; gap: 12px;
}
.empty-icon {
    width: 64px; height: 64px; border-radius: 16px;
    background: var(--surface-2); border: 1px solid var(--border-solid);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted); margin-bottom: 4px;
}
.empty-title { font-size: 15px; font-weight: 500; color: var(--text-secondary); margin: 0; }
.empty-sub { font-size: 13px; color: var(--text-muted); margin: 0; }

.notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 16px;
}

.note-card {
    background: var(--surface-2);
    border: 1px solid var(--border-solid);
    border-radius: 14px; padding: 18px;
    display: flex; flex-direction: column; gap: 10px;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
    position: relative; overflow: hidden;
}
.note-card:hover {
    border-color: rgba(35,169,189,0.3);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3), 0 0 0 1px rgba(35,169,189,0.1);
}
.card-accent-bar {
    position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: var(--card-accent, #23A9BD);
    opacity: 0.7;
    border-radius: 14px 14px 0 0;
}

.card-badges { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }
.badge-archived {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10px; font-weight: 600; letter-spacing: 0.7px;
    padding: 3px 8px; border-radius: 99px;
    background: var(--primary-dim); border: 1px solid var(--primary-border);
    color: var(--primary); text-transform: uppercase;
}
.badge-priority {
    font-size: 10.5px; font-weight: 500;
    padding: 2px 8px; border-radius: 99px;
    border: 1px solid transparent;
}

.note-title {
    font-size: 14.5px; font-weight: 600; color: var(--text-primary);
    line-height: 1.4; margin: 0;
}
.note-excerpt {
    font-size: 12.5px; color: var(--text-secondary); line-height: 1.75; flex: 1; margin: 0;
}

.note-meta { display: flex; gap: 6px; flex-wrap: wrap; }
.meta-badge {
    font-size: 11px; padding: 2px 9px; border-radius: 99px;
    background: color-mix(in srgb, var(--c) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--c) 25%, transparent);
    color: var(--c);
}
.tag-badge {
    font-size: 11px; color: var(--text-muted);
    padding: 2px 0;
}

.note-date {
    display: flex; align-items: center; gap: 5px;
    font-size: 11px; color: var(--text-muted);
}

.card-actions {
    display: flex; gap: 8px; padding-top: 12px;
    border-top: 1px solid var(--border-solid);
}
.btn-restore, .btn-delete {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    height: 32px; border-radius: 8px; font-size: 12px; font-weight: 500;
    cursor: pointer; border: 1px solid; transition: all 0.18s;
    font-family: inherit; background: none;
}
.btn-restore {
    color: var(--primary); border-color: var(--primary-border);
    background: var(--primary-dim);
}
.btn-restore:hover {
    background: rgba(35,169,189,0.2); border-color: rgba(35,169,189,0.5);
}
.btn-delete { color: var(--danger); border-color: rgba(226,75,74,0.2); }
.btn-delete:hover { background: rgba(226,75,74,0.1); border-color: rgba(226,75,74,0.4); }
.btn-restore:disabled, .btn-delete:disabled { opacity: 0.45; cursor: not-allowed; }

/* Responsive */
@media (max-width: 640px) {
    .notes-grid { grid-template-columns: 1fr; }
    .filter-bar { flex-direction: column; }
    .search-wrap { min-width: unset; }
    .page-heading { flex-direction: column; align-items: flex-start; }
}
@media (min-width: 641px) and (max-width: 1024px) {
    .notes-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>