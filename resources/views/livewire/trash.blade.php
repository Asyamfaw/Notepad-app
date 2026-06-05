<div class="trash-page">

    {{-- ── Flash Message ── --}}
    @if (session()->has('success'))
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

    {{-- ── Warning Banner ── --}}
    <div class="warning-banner" x-data="{ show: true }" x-show="show"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="warning-inner">
            <div class="warning-icon-wrap">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
            </div>
            <span>
                Items in trash will be <strong>permanently deleted after 30 days</strong>.
                You can restore notes anytime before they expire.
            </span>
        </div>
        <button class="warning-close" @click="show = false">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    {{-- ── Search Bar + Filter ── --}}
    <div class="search-bar">
        <div class="search-wrap">
            <svg class="search-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Search in trash..." class="search-input">
        </div>

        <select wire:model.live="category" class="filter-select">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        @if ($notes->isNotEmpty())
            <button onclick="confirmEmptyTrash()" class="btn-empty-trash">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14H6L5 6"/>
                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                </svg>
                Empty Trash Permanently
            </button>
        @endif
    </div>

    {{-- ── Heading ── --}}
    <div class="page-heading">
        <div>
            <h1 class="page-title">
                <span class="title-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14H6L5 6"/>
                        <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                    </svg>
                </span>
                Trash
            </h1>
            <p class="page-sub">
                <span class="count-badge {{ $notes->count() > 0 ? 'count-badge--warn' : '' }}">
                    {{ $notes->count() }}
                </span>
                notes
                @if ($notes->count() > 0)
                    · permanently deleted after 30 days
                @endif
            </p>
        </div>
    </div>

    {{-- ── Empty State ── --}}
    @if ($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M19 6l-1 14H6L5 6"/>
                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                </svg>
            </div>
            <p class="empty-title">Trash is empty</p>
            <p class="empty-sub">Notes you delete will appear here.</p>
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

                <div class="note-card {{ $isUrgent ? 'note-card--urgent' : '' }}"
                     wire:key="note-{{ $note->id }}">

                    <div class="card-top">
                        <span class="deleted-badge {{ $isUrgent ? 'deleted-badge--urgent' : '' }}">
                            <svg width="8" height="8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            {{ $daysAgo == 0 ? 'DELETED TODAY' : 'DELETED ' . $daysAgo . 'D AGO' }}
                        </span>
                        @if ($isUrgent)
                            <span class="expire-badge">
                                ⚠ {{ $daysLeft }}d left
                            </span>
                        @endif
                    </div>

                    <h3 class="note-title">{{ $note->title }}</h3>
                    <p class="note-excerpt">{{ Str::limit($note->content, 120) }}</p>

                    @if ($note->category)
                        <span class="category-badge"
                              style="--cat-color: {{ $note->category->color ?? '#23A9BD' }}">
                            {{ $note->category->name }}
                        </span>
                    @endif

                    <div class="card-actions">
                        <button wire:click="restore({{ $note->id }})"
                                wire:loading.attr="disabled"
                                wire:target="restore({{ $note->id }})"
                                class="btn-restore">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12a9 9 0 109-9 9.75 9.75 0 00-6.74 2.74L3 8"/>
                                <path d="M3 3v5h5"/>
                            </svg>
                            <span wire:loading.remove wire:target="restore({{ $note->id }})">Restore</span>
                            <span wire:loading wire:target="restore({{ $note->id }})">Restoring...</span>
                        </button>

                        <button onclick="confirmForceDelete({{ $note->id }})"
                                wire:loading.attr="disabled"
                                wire:target="forceDelete({{ $note->id }})"
                                class="btn-delete">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14H6L5 6"/>
                            </svg>
                            <span wire:loading.remove wire:target="forceDelete({{ $note->id }})">Permanent Delete</span>
                            <span wire:loading wire:target="forceDelete({{ $note->id }})">Deleting...</span>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmForceDelete(noteId) {
        Swal.fire({
            title: 'Delete Permanently?',
            text: "This action cannot be undone! The note will be gone forever.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E24B4A',
            cancelButtonColor: '#3a5568',
            confirmButtonText: 'Yes, delete forever!',
            cancelButtonText: 'Cancel',
            background: '#131b24',
            color: '#e8f4f8',
            customClass: {
                popup: 'swal-dark',
                title: 'swal-title',
                htmlContainer: 'swal-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('forceDelete', noteId);
                Swal.fire({
                    title: 'Deleted!',
                    text: 'The note has been permanently deleted.',
                    icon: 'success',
                    confirmButtonColor: '#23A9BD',
                    background: '#131b24',
                    color: '#e8f4f8',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    function confirmEmptyTrash() {
        Swal.fire({
            title: 'Empty Trash?',
            text: "All notes in trash will be permanently deleted. This cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E24B4A',
            cancelButtonColor: '#3a5568',
            confirmButtonText: 'Yes, delete all!',
            cancelButtonText: 'Cancel',
            background: '#131b24',
            color: '#e8f4f8',
            customClass: {
                popup: 'swal-dark',
                title: 'swal-title',
                htmlContainer: 'swal-text'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('emptyTrash');
                Swal.fire({
                    title: 'Trash Emptied!',
                    text: 'All notes have been permanently deleted.',
                    icon: 'success',
                    confirmButtonColor: '#23A9BD',
                    background: '#131b24',
                    color: '#e8f4f8',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700&display=swap');

.trash-page {
    font-family: 'Hanken Grotesk', 'Inter', sans-serif;
    --primary: #23A9BD;
    --primary-dim: rgba(35, 169, 189, 0.1);
    --primary-border: rgba(35, 169, 189, 0.22);
    --surface: #0d1117;
    --surface-2: #131b24;
    --surface-3: #1a2535;
    --border-solid: #1e2d3d;
    --text-primary: #e8f4f8;
    --text-secondary: #7a9bb0;
    --text-muted: #3a5568;
    --danger: #E24B4A;
    --warning: #EF9F27;
}

.flash-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(35,169,189,0.08); border: 1px solid rgba(35,169,189,0.25);
    color: #23A9BD; font-size: 13px; padding: 10px 14px;
    border-radius: 10px; margin-bottom: 16px;
}

.warning-banner {
    display: flex; align-items: center; justify-content: space-between; gap: 12px;
    background: rgba(218,132,66, 0.07);
    border: 1px solid rgba(218,132,66, 0.2);
    border-left: 3px solid #DA8642;
    border-radius: 10px; padding: 12px 16px; margin-bottom: 20px;
}
.warning-inner {
    display: flex; align-items: center; gap: 10px;
    color: #c17a3a; font-size: 13px; line-height: 1.6; flex: 1;
}
.warning-icon-wrap {
    width: 28px; height: 28px; border-radius: 7px; flex-shrink: 0;
    background: rgba(218,132,66,0.12); border: 1px solid rgba(218,132,66,0.2);
    display: flex; align-items: center; justify-content: center; color: #DA8642;
}
.warning-inner strong { color: #EF9F27; }
.warning-close {
    background: none; border: none; color: var(--text-muted);
    cursor: pointer; padding: 4px; flex-shrink: 0;
    transition: color 0.15s; border-radius: 5px;
}
.warning-close:hover { color: var(--text-secondary); background: rgba(255,255,255,0.05); }

.search-bar {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 20px; flex-wrap: wrap;
}
.search-wrap {
    flex: 1; min-width: 180px; position: relative;
    display: flex; align-items: center;
}
.search-icon { position: absolute; left: 12px; color: var(--text-muted); pointer-events: none; }
.search-input {
    width: 100%; height: 38px; padding: 0 12px 0 36px;
    background: var(--surface-2); border: 1px solid var(--border-solid); border-radius: 9px;
    color: var(--text-primary); font-size: 13px; outline: none; font-family: inherit;
    transition: border-color 0.18s;
}
.search-input:focus { border-color: var(--primary-border); box-shadow: 0 0 0 3px rgba(35,169,189,0.06); }
.search-input::placeholder { color: var(--text-muted); }

.filter-select {
    height: 38px; padding: 0 12px;
    background: var(--surface-2); border: 1px solid var(--border-solid); border-radius: 9px;
    color: var(--text-secondary); font-size: 13px; cursor: pointer; outline: none;
    font-family: inherit;
}

.btn-empty-trash {
    display: flex; align-items: center; gap: 6px;
    height: 38px; padding: 0 14px;
    background: rgba(226,75,74,0.08); border: 1px solid rgba(226,75,74,0.25);
    border-radius: 9px; color: var(--danger); font-size: 12.5px; font-weight: 500;
    cursor: pointer; transition: all 0.18s; white-space: nowrap; font-family: inherit;
}
.btn-empty-trash:hover { background: rgba(226,75,74,0.14); border-color: rgba(226,75,74,0.45); }

.page-heading {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 24px; flex-wrap: wrap; gap: 10px;
}
.page-title {
    display: flex; align-items: center; gap: 10px;
    font-size: 22px; font-weight: 600; color: var(--text-primary);
    letter-spacing: -0.4px; margin: 0 0 6px;
}
.title-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: rgba(226,75,74,0.1); border: 1px solid rgba(226,75,74,0.2);
    display: flex; align-items: center; justify-content: center;
    color: var(--danger); flex-shrink: 0;
}
.page-sub {
    font-size: 13px; color: var(--text-muted);
    display: flex; align-items: center; gap: 6px;
}
.count-badge {
    display: inline-flex; align-items: center; justify-content: center;
    background: rgba(35,169,189,0.1); border: 1px solid rgba(35,169,189,0.2);
    color: var(--primary); font-size: 11px; font-weight: 600;
    padding: 1px 7px; border-radius: 99px;
}
.count-badge--warn {
    background: rgba(226,75,74,0.1); border-color: rgba(226,75,74,0.2);
    color: var(--danger);
}

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
    background: var(--surface-2); border: 1px solid var(--border-solid);
    border-radius: 14px; padding: 18px;
    display: flex; flex-direction: column; gap: 10px;
    transition: border-color 0.2s, transform 0.2s, box-shadow 0.2s;
    position: relative; overflow: hidden;
}
.note-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 2px;
    background: linear-gradient(90deg, var(--text-muted), transparent);
    opacity: 0.5;
}
.note-card:hover {
    border-color: rgba(35,169,189,0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.3);
}
.note-card--urgent {
    border-color: rgba(226,75,74,0.2);
    background: linear-gradient(135deg, rgba(226,75,74,0.04), var(--surface-2));
}
.note-card--urgent::before {
    background: linear-gradient(90deg, var(--danger), transparent);
    opacity: 0.6;
}
.note-card--urgent:hover { border-color: rgba(226,75,74,0.4); }

.card-top {
    display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
}
.deleted-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 10px; font-weight: 600; letter-spacing: 0.8px;
    color: var(--text-muted); background: rgba(255,255,255,0.04);
    border: 1px solid var(--border-solid);
    padding: 3px 9px; border-radius: 99px; text-transform: uppercase;
}
.deleted-badge--urgent {
    color: var(--danger); background: rgba(226,75,74,0.08);
    border-color: rgba(226,75,74,0.25);
}
.expire-badge {
    font-size: 10px; font-weight: 600; color: var(--warning);
    background: rgba(239,159,39,0.1); border: 1px solid rgba(239,159,39,0.25);
    padding: 3px 8px; border-radius: 99px; letter-spacing: 0.4px;
}

.note-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin: 0; line-height: 1.4; }
.note-excerpt { font-size: 13px; color: var(--text-secondary); line-height: 1.75; margin: 0; flex: 1; }

.category-badge {
    display: inline-flex; align-items: center;
    font-size: 11px; padding: 3px 9px; border-radius: 99px;
    background: color-mix(in srgb, var(--cat-color) 12%, transparent);
    border: 1px solid color-mix(in srgb, var(--cat-color) 28%, transparent);
    color: var(--cat-color); width: fit-content;
}

.card-actions {
    display: flex; gap: 8px; padding-top: 12px;
    border-top: 1px solid var(--border-solid);
}
.btn-restore, .btn-delete {
    flex: 1; display: flex; align-items: center; justify-content: center; gap: 6px;
    height: 33px; border-radius: 8px; font-size: 12px; font-weight: 500;
    cursor: pointer; border: 1px solid; transition: all 0.18s;
    font-family: inherit; background: none;
}
.btn-restore {
    color: var(--primary); border-color: var(--primary-border);
    background: var(--primary-dim);
}
.btn-restore:hover {
    background: rgba(35,169,189,0.18); border-color: rgba(35,169,189,0.45);
}
.btn-delete { color: var(--danger); border-color: rgba(226,75,74,0.2); }
.btn-delete:hover { background: rgba(226,75,74,0.1); border-color: rgba(226,75,74,0.4); }
.btn-restore:disabled, .btn-delete:disabled { opacity: 0.45; cursor: not-allowed; }

.swal-dark {
    background: #131b24 !important;
    border: 1px solid #1e2d3d !important;
    border-radius: 14px !important;
}
.swal-title { color: #e8f4f8 !important; }
.swal-text { color: #7a9bb0 !important; }

@media (max-width: 640px) {
    .notes-grid { grid-template-columns: 1fr; }
    .search-bar { flex-direction: column; }
    .btn-empty-trash { width: 100%; justify-content: center; }
    .search-wrap, .filter-select { width: 100%; min-width: unset; }
}
@media (min-width: 641px) and (max-width: 1024px) {
    .notes-grid { grid-template-columns: repeat(2, 1fr); }
}
</style>