<div class="dashboard-page">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-sub">Good morning, Alex. You have {{ $notes->where('priority', 'high')->count() }} important notes to review today.</p>
        </div>
        <a href="{{ route('notes.create') }}" class="btn-new">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            New Note
        </a>
    </div>

    {{-- ── Filter Bar ── --}}
<div class="filter-bar">
    <div class="search-wrap">
        <svg class="search-icon" ...>...</svg>
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search notes..."
            class="search-input"
        >
    </div>

    <select wire:model.live="category" class="filter-select">
        <option value="">All categories</option>
        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach
    </select>

    <select wire:model.live="priority" class="filter-select">
        <option value="">All priorities</option>
        <option value="high">🔴 High</option>
        <option value="medium">🟡 Medium</option>
        <option value="low">🟢 Low</option>
    </select>
</div>

    {{-- ── Quick Stats ── --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-value">{{ $notes->count() }}</div>
            <div class="stat-label">Total Notes</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $notes->where('is_pinned', true)->count() }}</div>
            <div class="stat-label">Pinned</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $notes->whereNotNull('deadline')->where('deadline', '>=', now())->count() }}</div>
            <div class="stat-label">Upcoming</div>
        </div>
    </div>

    {{-- ── Empty State ── --}}
    @if($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <p class="empty-title">
                {{ $search || $category || $priority ? 'No matching notes found' : 'No notes yet' }}
            </p>
            <p class="empty-sub">
                {{ $search || $category || $priority ? 'Try adjusting your filters.' : 'Start by creating your first note.' }}
            </p>
            @if(!$search && !$category && !$priority)
                <a href="{{ route('notes.create') }}" class="btn-new" style="margin-top:0.5rem;">Create Note</a>
            @endif
        </div>

    {{-- ── Notes Grid ── --}}
    @else
        <div class="notes-grid">
            @foreach($notes as $note)
                <div class="note-card {{ $note->is_pinned ? 'note-card--pinned' : '' }}"
                     wire:key="note-{{ $note->id }}"
                     style="border-top-color: {{ $note->color_label ?? '#23A9BD' }}">

                    {{-- Top badges --}}
                    <div class="card-badges">
                        @if($note->is_pinned)
                            <span class="badge badge-pin">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                PINNED
                            </span>
                        @endif
                        @php
                            $pColor = ['high'=>'#DA8642','medium'=>'#23A9BD','low'=>'#94A3B8'];
                            $pLabel = ['high'=>'HIGH','medium'=>'MEDIUM','low'=>'LOW'];
                        @endphp
                        <span class="badge priority-badge" style="color:{{ $pColor[$note->priority] ?? '#94A3B8' }};background:color-mix(in srgb,{{ $pColor[$note->priority] ?? '#94A3B8' }} 12%,transparent);border-color:color-mix(in srgb,{{ $pColor[$note->priority] ?? '#94A3B8' }} 25%,transparent)">
                            {{ $pLabel[$note->priority] ?? $note->priority }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="note-title">{{ $note->title }}</h3>

                    {{-- Excerpt --}}
                    <p class="note-excerpt">{{ Str::limit($note->content, 120) }}</p>

                    {{-- Category & Tags --}}
                    <div class="note-meta">
                        @if($note->category)
                            <span class="meta-badge" style="--c:{{ $note->category->color ?? '#23A9BD' }}">
                                {{ $note->category->name }}
                            </span>
                        @endif
                        @foreach($note->tags->take(3) as $tag)
                            <span class="tag-badge">#{{ $tag->name }}</span>
                        @endforeach
                        @if($note->deadline)
                            <span class="deadline-badge {{ $note->deadline->isPast() ? 'deadline-late' : '' }}">
                                📅 {{ $note->deadline->format('d M Y') }}
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
                        <button onclick="confirmArchive({{ $note->id }})" class="btn-action btn-archive">
                            Archive
                        </button>
                        <button onclick="confirmDelete({{ $note->id }})" class="btn-action btn-delete">
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(noteId) {
        Swal.fire({
            title: 'Delete Note?',
            text: "This action cannot be undone! The note will be permanently deleted.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DA8642',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            background: '#0D1117',
            color: '#e0e0e0',
            customClass: {
                popup: 'swal-dark',
                title: 'swal-title',
                htmlContainer: 'swal-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('delete', noteId);
                Swal.fire({
                    title: 'Deleted!',
                    text: 'Your note has been deleted.',
                    icon: 'success',
                    confirmButtonColor: '#23A9BD',
                    background: '#0D1117',
                    color: '#e0e0e0',
                    timer: 2000,
                    showConfirmButton: true
                });
            }
        });
    }

    function confirmArchive(noteId) {
        Swal.fire({
            title: 'Archive Note?',
            text: "This note will be moved to archive. You can restore it anytime.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#23A9BD',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, archive it!',
            cancelButtonText: 'Cancel',
            background: '#0D1117',
            color: '#e0e0e0',
            customClass: {
                popup: 'swal-dark',
                title: 'swal-title',
                htmlContainer: 'swal-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('archive', noteId);
                Swal.fire({
                    title: 'Archived!',
                    text: 'Your note has been moved to archive.',
                    icon: 'success',
                    confirmButtonColor: '#23A9BD',
                    background: '#0D1117',
                    color: '#e0e0e0',
                    timer: 2000,
                    showConfirmButton: true
                });
            }
        });
    }
</script>

<style>
.dashboard-page { font-family: 'Inter', sans-serif; max-width: 1400px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #0A0C0F 0%, #0D1117 100%); min-height: 100vh; }

.page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem; }
.page-title { font-size: 2rem; font-weight: 600; background: linear-gradient(135deg, #23A9BD 0%, #0D4E59 100%); -webkit-background-clip: text; background-clip: text; color: transparent; letter-spacing: -0.5px; }
.page-sub { font-size: 0.875rem; color: #94A3B8; margin-top: 0.25rem; }

.btn-new {
    display: inline-flex; align-items: center; gap: 0.5rem;
    height: 40px; padding: 0 1.25rem;
    background: #23A9BD; border: none; border-radius: 10px;
    color: #fff; font-size: 0.875rem; font-weight: 500;
    text-decoration: none; cursor: pointer; transition: all 0.2s;
}
.btn-new:hover { background: #1D8FA0; transform: translateY(-1px); }

.filter-bar { display: flex; gap: 0.75rem; margin-bottom: 2rem; flex-wrap: wrap; }
.search-wrap { flex: 1; min-width: 200px; position: relative; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94A3B8; }
.search-input {
    width: 100%; height: 42px; padding: 0 12px 0 36px;
    background: #0D1117; border: 1px solid rgba(35, 169, 189, 0.2); border-radius: 10px;
    color: #e0e0e0; font-size: 0.875rem; outline: none; transition: all 0.2s;
}
.search-input:focus { border-color: #23A9BD; box-shadow: 0 0 0 2px rgba(35, 169, 189, 0.1); }
.filter-select {
    height: 42px; padding: 0 1rem;
    background: #0D1117; border: 1px solid rgba(35, 169, 189, 0.2); border-radius: 10px;
    color: #94A3B8; font-size: 0.875rem; cursor: pointer; outline: none;
}
.filter-select:focus { border-color: #23A9BD; }

.stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.stat-card { background: rgba(13, 78, 89, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(35, 169, 189, 0.15); border-radius: 1rem; padding: 1rem; text-align: center; transition: all 0.2s; }
.stat-card:hover { border-color: rgba(35, 169, 189, 0.3); transform: translateY(-2px); }
.stat-value { font-size: 2rem; font-weight: 700; color: #23A9BD; }
.stat-label { font-size: 0.75rem; color: #94A3B8; margin-top: 0.25rem; text-transform: uppercase; letter-spacing: 0.5px; }

.empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 0; gap: 0.75rem; }
.empty-icon { width: 80px; height: 80px; border-radius: 1rem; background: rgba(13, 78, 89, 0.05); border: 1px solid rgba(35, 169, 189, 0.15); display: flex; align-items: center; justify-content: center; color: #23A9BD; margin-bottom: 0.5rem; }
.empty-title { font-size: 1rem; font-weight: 500; color: #e0e0e0; }
.empty-sub { font-size: 0.875rem; color: #94A3B8; }

.notes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem; }

.note-card { background: rgba(13, 78, 89, 0.05); backdrop-filter: blur(8px); border: 1px solid rgba(35, 169, 189, 0.12); border-radius: 1rem; padding: 1.25rem; border-top-width: 3px; transition: all 0.2s; display: flex; flex-direction: column; gap: 0.75rem; }
.note-card:hover { border-color: rgba(35, 169, 189, 0.3); transform: translateY(-2px); }
.note-card--pinned { background: rgba(35, 169, 189, 0.05); border-color: rgba(35, 169, 189, 0.25); }

.card-badges { display: flex; gap: 0.5rem; flex-wrap: wrap; }
.badge { font-size: 0.625rem; font-weight: 600; letter-spacing: 0.5px; padding: 0.25rem 0.625rem; border-radius: 99px; }
.badge-pin { color: #23A9BD; background: rgba(35, 169, 189, 0.1); border: 1px solid rgba(35, 169, 189, 0.2); }
.priority-badge { border: 1px solid; }

.note-title { font-size: 1rem; font-weight: 600; color: #ffffff; line-height: 1.4; }
.note-excerpt { font-size: 0.813rem; color: #94A3B8; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }

.note-meta { display: flex; gap: 0.5rem; flex-wrap: wrap; align-items: center; margin-top: auto; }
.meta-badge { font-size: 0.688rem; padding: 0.25rem 0.625rem; border-radius: 99px; background: color-mix(in srgb, var(--c) 12%, transparent); border: 1px solid color-mix(in srgb, var(--c) 25%, transparent); color: var(--c); }
.tag-badge { font-size: 0.688rem; color: #94A3B8; }
.deadline-badge { font-size: 0.688rem; color: #23A9BD; background: rgba(35, 169, 189, 0.1); border: 1px solid rgba(35, 169, 189, 0.2); padding: 0.25rem 0.625rem; border-radius: 99px; }
.deadline-late { color: #DA8642; background: rgba(218, 134, 66, 0.1); border-color: rgba(218, 134, 66, 0.2); }

.card-actions { display: flex; gap: 0.5rem; padding-top: 0.75rem; margin-top: 0.25rem; border-top: 1px solid rgba(255, 255, 255, 0.05); }
.btn-action { display: inline-flex; align-items: center; justify-content: center; gap: 0.375rem; height: 30px; padding: 0 0.75rem; border-radius: 8px; font-size: 0.688rem; font-weight: 500; cursor: pointer; border: 1px solid; transition: all 0.2s; background: none; text-decoration: none; }
.btn-edit { color: #23A9BD; border-color: rgba(35, 169, 189, 0.25); }
.btn-edit:hover { background: rgba(35, 169, 189, 0.1); }
.btn-pin { color: #94A3B8; border-color: rgba(148, 163, 184, 0.25); }
.btn-pin:hover { color: #23A9BD; border-color: rgba(35, 169, 189, 0.4); }
.btn-archive { color: #DA8642; border-color: rgba(218, 134, 66, 0.25); }
.btn-archive:hover { background: rgba(218, 134, 66, 0.08); }
.btn-delete { color: #DA8642; border-color: rgba(218, 134, 66, 0.2); margin-left: auto; }
.btn-delete:hover { background: rgba(218, 134, 66, 0.08); }

/* SweetAlert Dark Mode Customization */
.swal-dark {
    background: #0D1117 !important;
    border: 1px solid rgba(35, 169, 189, 0.3) !important;
    border-radius: 1rem !important;
}
.swal-title {
    color: #ffffff !important;
}
.swal-text {
    color: #94A3B8 !important;
}

@media (max-width: 768px) {
    .dashboard-page { padding: 1rem; }
    .notes-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: repeat(3, 1fr); }
}
</style>