<div class="dashboard">

    {{-- Flash --}}
    @if (session()->has('success'))
        <div class="flash-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             x-transition:leave="transition duration-300" x-transition:leave-end="opacity-0">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Search Bar --}}
    <div class="search-bar">
        <div class="search-wrap">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" wire:model.debounce.300ms="search" placeholder="Search across your workspace..." class="search-input">
        </div>
        <a href="{{ route('notes.create') }}" class="btn-new-note">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Note
        </a>
    </div>

    {{-- Greeting --}}
    <div class="greeting-row">
        <div>
            <h1 class="greeting-title">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                {{ explode(' ', Auth::user()->name)[0] }}.
            </h1>
            <p class="greeting-sub">
                You have <strong>{{ $notes->where('priority', 'high')->count() }}</strong> important notes to review today.
            </p>
        </div>

        {{-- Filter pills --}}
        <div class="filter-pills">
            <button wire:click="$set('priority', {{ $priority === 'high' ? "''" : "'high'" }})"
                class="filter-pill {{ $priority === 'high' ? 'active' : '' }}">
                📌 Pinned
            </button>
            <select wire:model="category" class="filter-select">
                <option value="">Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <select wire:model="priority" class="filter-select">
                <option value="">Priority</option>
                <option value="high">High</option>
                <option value="medium">Medium</option>
                <option value="low">Low</option>
            </select>
        </div>
    </div>

    {{-- Notes Grid --}}
    @if ($notes->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <p class="empty-title">Belum ada catatan</p>
            <p class="empty-sub">Klik "+ New Note" untuk membuat catatan pertama.</p>
        </div>
    @else
        <div class="notes-grid">
            @foreach ($notes as $note)
                <div class="note-card {{ $note->is_pinned ? 'note-card--pinned' : '' }}" wire:key="note-{{ $note->id }}"
                     style="{{ $note->color_label ? '--card-accent: ' . $note->color_label : '' }}">

                    {{-- Top badges --}}
                    <div class="card-top">
                        <div class="card-badges">
                            @if ($note->category)
                                <span class="badge badge-category">{{ $note->category->name }}</span>
                            @endif
                            @if ($note->priority === 'high')
                                <span class="badge badge-high">HIGH PRIORITY</span>
                            @elseif ($note->priority === 'medium')
                                <span class="badge badge-medium">MEDIUM</span>
                            @endif
                        </div>
                        @if ($note->is_pinned)
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" style="color:#555">
                                <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Title & Content --}}
                    <h3 class="note-title">{{ $note->title }}</h3>
                    <p class="note-excerpt">{{ Str::limit($note->content, 140) }}</p>

                    {{-- Tags --}}
                    @if ($note->tags->isNotEmpty())
                        <div class="note-tags">
                            @foreach ($note->tags->take(3) as $tag)
                                <span class="tag-chip">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="card-footer">
                        <span class="note-date">{{ $note->updated_at->diffForHumans() }}</span>
                        <div class="card-actions">
                            <a href="{{ route('notes.edit', $note->id) }}" class="action-btn" title="Edit">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <button wire:click="togglePin({{ $note->id }})" class="action-btn" title="Pin">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="{{ $note->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                    <path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/>
                                </svg>
                            </button>
                            <button wire:click="archive({{ $note->id }})" class="action-btn" title="Archive">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/>
                                </svg>
                            </button>
                            <button wire:click="delete({{ $note->id }})" wire:confirm="Pindahkan ke sampah?" class="action-btn action-btn--danger" title="Delete">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

<style>
.dashboard { max-width: 1100px; margin: 0 auto; padding: 0 0 48px; }

/* Flash */
.flash-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(99,153,34,.12); border: 1px solid rgba(99,153,34,.3);
    color: #97C459; font-size: 13px; padding: 10px 14px; border-radius: 8px; margin-bottom: 16px;
}

/* Search Bar */
.search-bar { display: flex; align-items: center; gap: 10px; padding: 14px 0; border-bottom: 1px solid #1a1a1a; margin-bottom: 24px; }
.search-wrap { flex: 1; display: flex; align-items: center; gap: 10px; color: #555; }
.search-input { flex: 1; background: none; border: none; outline: none; font-size: 14px; color: #e0ddd8; }
.search-input::placeholder { color: #444; }
.btn-new-note {
    display: flex; align-items: center; gap: 6px; height: 34px; padding: 0 14px;
    background: #e8e6e0; color: #111; border-radius: 7px; font-size: 13px;
    font-weight: 500; text-decoration: none; white-space: nowrap; transition: opacity .15s;
}
.btn-new-note:hover { opacity: .85; }

/* Greeting */
.greeting-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 16px; margin-bottom: 24px; flex-wrap: wrap; }
.greeting-title { font-size: 26px; font-weight: 400; color: #e8e6e0; letter-spacing: -.4px; margin-bottom: 6px; }
.greeting-sub { font-size: 13px; color: #555; }
.greeting-sub strong { color: #888; }
.filter-pills { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.filter-pill { height: 30px; padding: 0 12px; background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 99px; font-size: 12px; color: #666; cursor: pointer; transition: all .15s; }
.filter-pill.active, .filter-pill:hover { border-color: #3a3a3a; color: #aaa; }
.filter-select { height: 30px; padding: 0 10px; background: #1a1a1a; border: 1px solid #2a2a2a; border-radius: 99px; font-size: 12px; color: #666; cursor: pointer; outline: none; }

/* Empty */
.empty-state { display: flex; flex-direction: column; align-items: center; padding: 80px 0; gap: 10px; }
.empty-icon { width: 56px; height: 56px; background: #1a1a1a; border: 1px solid #222; border-radius: 14px; display: flex; align-items: center; justify-content: center; color: #333; }
.empty-title { font-size: 15px; font-weight: 500; color: #555; }
.empty-sub { font-size: 13px; color: #3a3a3a; }

/* Notes Grid */
.notes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 14px; }

/* Note Card */
.note-card {
    background: #111; border: 1px solid #1e1e1e; border-radius: 12px;
    padding: 18px; display: flex; flex-direction: column; gap: 10px;
    transition: border-color .15s; cursor: default;
    border-left: 3px solid var(--card-accent, #1e1e1e);
}
.note-card:hover { border-color: #2a2a2a; border-left-color: var(--card-accent, #2a2a2a); }
.note-card--pinned { border-left-color: #378ADD; }

.card-top { display: flex; align-items: center; justify-content: space-between; }
.card-badges { display: flex; gap: 6px; flex-wrap: wrap; }
.badge { font-size: 10px; font-weight: 500; letter-spacing: .6px; padding: 3px 8px; border-radius: 99px; }
.badge-category { background: #1e1e1e; border: 1px solid #2a2a2a; color: #666; }
.badge-high { background: rgba(226,75,74,.1); border: 1px solid rgba(226,75,74,.25); color: #E24B4A; }
.badge-medium { background: rgba(239,159,39,.1); border: 1px solid rgba(239,159,39,.25); color: #EF9F27; }

.note-title { font-size: 15px; font-weight: 500; color: #d0cec8; line-height: 1.4; }
.note-excerpt { font-size: 13px; color: #555; line-height: 1.7; flex: 1; }

.note-tags { display: flex; gap: 6px; flex-wrap: wrap; }
.tag-chip { font-size: 11px; color: #555; background: #1a1a1a; padding: 2px 7px; border-radius: 4px; }

.card-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 10px; border-top: 1px solid #1a1a1a; margin-top: 2px; }
.note-date { font-size: 11px; color: #3a3a3a; }
.card-actions { display: flex; gap: 2px; }
.action-btn { display: flex; align-items: center; justify-content: center; width: 26px; height: 26px; background: none; border: none; border-radius: 5px; color: #444; cursor: pointer; transition: all .15s; }
.action-btn:hover { background: #1e1e1e; color: #aaa; }
.action-btn--danger:hover { background: rgba(226,75,74,.1); color: #E24B4A; }
</style>