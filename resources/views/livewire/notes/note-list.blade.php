<div class="dashboard-page">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-sub">Semua catatan aktif kamu</p>
        </div>
        <a href="{{ route('notes.create') }}" class="btn-new">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Catatan Baru
        </a>
    </div>

    {{-- ── Filter Bar ── --}}
    <div class="filter-bar">
        <div class="search-wrap">
            <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Cari catatan..."
                class="search-input"
            >
        </div>

        <select wire:model="category" class="filter-select">
            <option value="">Semua kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <select wire:model="priority" class="filter-select">
            <option value="">Semua prioritas</option>
            <option value="high">🔴 Tinggi</option>
            <option value="medium">🟡 Sedang</option>
            <option value="low">🟢 Rendah</option>
        </select>
    </div>

    {{-- ── Empty State ── --}}
    @if($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <p class="empty-title">
                {{ $search || $category || $priority ? 'Tidak ada catatan yang cocok' : 'Belum ada catatan' }}
            </p>
            <p class="empty-sub">
                {{ $search || $category || $priority ? 'Coba ubah filter pencarian.' : 'Mulai dengan membuat catatan pertamamu.' }}
            </p>
            @if(!$search && !$category && !$priority)
                <a href="{{ route('notes.create') }}" class="btn-new" style="margin-top:8px;">Buat Catatan</a>
            @endif
        </div>

    {{-- ── Notes Grid ── --}}
    @else
        <div class="notes-grid">
            @foreach($notes as $note)
                <div class="note-card {{ $note->is_pinned ? 'note-card--pinned' : '' }}"
                     wire:key="note-{{ $note->id }}"
                     @if($note->color_label) style="border-top: 2px solid {{ $note->color_label }}" @endif>

                    {{-- Top badges --}}
                    <div class="card-badges">
                        @if($note->is_pinned)
                            <span class="badge badge-pin">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                PIN
                            </span>
                        @endif
                        @php
                            $pColor = ['high'=>'#E24B4A','medium'=>'#EF9F27','low'=>'#97C459'];
                            $pLabel = ['high'=>'Tinggi','medium'=>'Sedang','low'=>'Rendah'];
                        @endphp
                        <span class="badge" style="color:{{ $pColor[$note->priority] ?? '#999' }};background:color-mix(in srgb,{{ $pColor[$note->priority] ?? '#999' }} 12%,transparent);border:1px solid color-mix(in srgb,{{ $pColor[$note->priority] ?? '#999' }} 25%,transparent)">
                            {{ $pLabel[$note->priority] ?? $note->priority }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="note-title">{{ $note->title }}</h3>

                    {{-- Excerpt --}}
                    <p class="note-excerpt">{{ Str::limit($note->content, 100) }}</p>

                    {{-- Category & Tags --}}
                    <div class="note-meta">
                        @if($note->category)
                            <span class="meta-badge" style="--c:{{ $note->category->color ?? '#888' }}">
                                {{ $note->category->name }}
                            </span>
                        @endif
                        @foreach($note->tags->take(2) as $tag)
                            <span class="tag-badge">#{{ $tag->name }}</span>
                        @endforeach
                        @if($note->deadline)
                            <span class="deadline-badge {{ $note->deadline->isPast() ? 'deadline-late' : '' }}">
                                {{ $note->deadline->format('d M') }}
                            </span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="card-actions">
                        <a href="{{ route('notes.edit', $note->id) }}" class="btn-action btn-edit">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </a>
                        <button wire:click="togglePin({{ $note->id }})" class="btn-action btn-pin">
                            {{ $note->is_pinned ? 'Unpin' : 'Pin' }}
                        </button>
                        <button wire:click="archive({{ $note->id }})" class="btn-action btn-archive"
                                wire:confirm="Pindahkan ke arsip?">
                            Arsip
                        </button>
                        <button wire:click="delete({{ $note->id }})" class="btn-action btn-delete"
                                wire:confirm="Hapus catatan ini?">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.dashboard-page { font-family: 'Inter', sans-serif; }

.page-header {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 20px;
}
.page-title { font-size: 22px; font-weight: 500; color: #e8e6e0; letter-spacing: -0.3px; }
.page-sub { font-size: 13px; color: #555; margin-top: 2px; }

.btn-new {
    display: flex; align-items: center; gap: 6px;
    height: 36px; padding: 0 14px;
    background: #378ADD; border: none; border-radius: 8px;
    color: #fff; font-size: 13px; font-weight: 500;
    text-decoration: none; cursor: pointer;
    transition: background 0.15s; white-space: nowrap;
}
.btn-new:hover { background: #2e72c9; }

.filter-bar {
    display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;
}
.search-wrap { flex: 1; min-width: 180px; position: relative; display: flex; align-items: center; }
.search-icon { position: absolute; left: 11px; color: #555; pointer-events: none; }
.search-input {
    width: 100%; height: 36px; padding: 0 12px 0 34px;
    background: #111; border: 1px solid #222; border-radius: 8px;
    color: #e0e0e0; font-size: 13px; outline: none;
    transition: border-color 0.15s; font-family: inherit;
}
.search-input:focus { border-color: #333; }
.search-input::placeholder { color: #444; }
.filter-select {
    height: 36px; padding: 0 12px;
    background: #111; border: 1px solid #222;
    border-radius: 8px; color: #888; font-size: 13px;
    cursor: pointer; outline: none; font-family: inherit;
}

.empty-state {
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 80px 0; gap: 10px;
}
.empty-icon {
    width: 60px; height: 60px; border-radius: 14px;
    background: #111; border: 1px solid #222;
    display: flex; align-items: center; justify-content: center; color: #333;
    margin-bottom: 4px;
}
.empty-title { font-size: 15px; font-weight: 500; color: #555; }
.empty-sub { font-size: 13px; color: #3a3a3a; }

.notes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
    gap: 14px;
}

.note-card {
    background: #111; border: 1px solid #1e1e1e;
    border-radius: 12px; padding: 16px;
    display: flex; flex-direction: column; gap: 8px;
    transition: border-color 0.15s;
}
.note-card:hover { border-color: #2a2a2a; }
.note-card--pinned { border-color: rgba(55,138,221,0.2); background: rgba(55,138,221,0.03); }

.card-badges { display: flex; gap: 6px; flex-wrap: wrap; }
.badge {
    font-size: 10px; font-weight: 500; letter-spacing: 0.5px;
    padding: 3px 8px; border-radius: 99px;
}
.badge-pin { color: #378ADD; background: rgba(55,138,221,0.1); border: 1px solid rgba(55,138,221,0.2); }

.note-title { font-size: 14.5px; font-weight: 500; color: #c8c6c0; line-height: 1.4; }
.note-excerpt { font-size: 12.5px; color: #505050; line-height: 1.7; flex: 1; }

.note-meta { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
.meta-badge {
    font-size: 11px; padding: 2px 8px; border-radius: 99px;
    background: color-mix(in srgb, var(--c) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--c) 25%, transparent);
    color: var(--c);
}
.tag-badge { font-size: 11px; color: #555; }
.deadline-badge { font-size: 11px; color: #EF9F27; background: rgba(239,159,39,0.1); border: 1px solid rgba(239,159,39,0.2); padding: 2px 7px; border-radius: 99px; }
.deadline-late { color: #E24B4A; background: rgba(226,75,74,0.1); border-color: rgba(226,75,74,0.2); }

.card-actions {
    display: flex; gap: 6px;
    padding-top: 10px; margin-top: 2px;
    border-top: 1px solid #1a1a1a;
}
.btn-action {
    display: flex; align-items: center; justify-content: center; gap: 4px;
    height: 28px; padding: 0 10px;
    border-radius: 6px; font-size: 11.5px;
    cursor: pointer; border: 1px solid;
    transition: all 0.15s; text-decoration: none; font-family: inherit;
    background: none;
}
.btn-edit { color: #378ADD; border-color: rgba(55,138,221,0.25); }
.btn-edit:hover { background: rgba(55,138,221,0.1); }
.btn-pin { color: #888; border-color: #2a2a2a; }
.btn-pin:hover { color: #e0e0e0; border-color: #444; }
.btn-archive { color: #EF9F27; border-color: rgba(239,159,39,0.25); }
.btn-archive:hover { background: rgba(239,159,39,0.08); }
.btn-delete { color: #E24B4A; border-color: rgba(226,75,74,0.2); margin-left: auto; }
.btn-delete:hover { background: rgba(226,75,74,0.08); }
</style>
