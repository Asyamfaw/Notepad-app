<div class="cat-page">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Kategori & Tag</h1>
            <p class="page-sub">Kelola kategori dan tag catatanmu</p>
        </div>
    </div>

    {{-- ── Flash ── --}}
    @if(session()->has('success'))
        <div class="flash-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="two-col">

        {{-- ──────── KATEGORI ──────── --}}
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Kategori</h2>
                <span class="panel-count">{{ $categories->count() }}</span>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="saveCategory" class="add-form">
                <input type="text" wire:model.defer="cat_name"
                       class="form-input" placeholder="Nama kategori...">
                <input type="color" wire:model.defer="cat_color"
                       title="Pilih warna"
                       style="height:36px;width:40px;border:1px solid #2a2a2a;border-radius:7px;background:#111;cursor:pointer;padding:2px;flex-shrink:0;">
                <button type="submit" class="btn-add" wire:loading.attr="disabled">
                    {{ $editing_cat_id ? 'Simpan' : '+' }}
                </button>
                @if($editing_cat_id)
                    <button type="button" wire:click="$set('editing_cat_id', null)" class="btn-cancel-edit">✕</button>
                @endif
            </form>
            @error('cat_name') <p class="form-error">{{ $message }}</p> @enderror

            {{-- List --}}
            @if($categories->isEmpty())
                <p class="list-empty">Belum ada kategori</p>
            @else
                <ul class="item-list">
                    @foreach($categories as $cat)
                        <li class="item-row" wire:key="cat-{{ $cat->id }}">
                            <span class="item-dot" style="background: {{ $cat->color ?? '#555' }}"></span>
                            <span class="item-name">{{ $cat->name }}</span>
                            <span class="item-count">{{ $cat->notes_count }} catatan</span>
                            <div class="item-actions">
                                <button wire:click="editCategory({{ $cat->id }})" class="btn-icon" title="Edit">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteCategory({{ $cat->id }})"
                                        wire:confirm="Hapus kategori ini?" class="btn-icon btn-icon-red" title="Hapus">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- ──────── TAG ──────── --}}
        <div class="panel">
            <div class="panel-header">
                <h2 class="panel-title">Tag</h2>
                <span class="panel-count">{{ $tags->count() }}</span>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="saveTag" class="add-form">
                <input type="text" wire:model.defer="tag_name"
                       class="form-input" placeholder="Nama tag...">
                <button type="submit" class="btn-add" wire:loading.attr="disabled">
                    {{ $editing_tag_id ? 'Simpan' : '+' }}
                </button>
                @if($editing_tag_id)
                    <button type="button" wire:click="$set('editing_tag_id', null)" class="btn-cancel-edit">✕</button>
                @endif
            </form>
            @error('tag_name') <p class="form-error">{{ $message }}</p> @enderror

            {{-- List --}}
            @if($tags->isEmpty())
                <p class="list-empty">Belum ada tag</p>
            @else
                <div class="tags-cloud">
                    @foreach($tags as $tag)
                        <div class="tag-item" wire:key="tag-{{ $tag->id }}">
                            <span class="tag-name">#{{ $tag->name }}</span>
                            <span class="tag-count">{{ $tag->notes_count }}</span>
                            <div class="item-actions">
                                <button wire:click="editTag({{ $tag->id }})" class="btn-icon" title="Edit">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button wire:click="deleteTag({{ $tag->id }})"
                                        wire:confirm="Hapus tag ini?" class="btn-icon btn-icon-red" title="Hapus">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.cat-page { font-family: 'Inter', sans-serif; }
.page-header { margin-bottom: 20px; }
.page-title { font-size: 22px; font-weight: 500; color: #e8e6e0; letter-spacing: -0.3px; }
.page-sub { font-size: 13px; color: #555; margin-top: 2px; }

.flash-success {
    display: flex; align-items: center; gap: 8px;
    background: rgba(151,196,89,0.1); border: 1px solid rgba(151,196,89,0.25);
    color: #97C459; font-size: 13px; padding: 10px 14px;
    border-radius: 8px; margin-bottom: 16px;
}

.two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

.panel {
    background: #111; border: 1px solid #1e1e1e;
    border-radius: 12px; padding: 20px;
    display: flex; flex-direction: column; gap: 14px;
}
.panel-header { display: flex; align-items: center; gap: 10px; }
.panel-title { font-size: 15px; font-weight: 500; color: #c8c6c0; }
.panel-count {
    font-size: 11px; padding: 2px 8px;
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 99px; color: #666;
}

.add-form { display: flex; gap: 8px; align-items: center; }
.form-input {
    flex: 1; height: 36px; padding: 0 10px;
    background: #0a0a0a; border: 1px solid #222; border-radius: 7px;
    color: #e0e0e0; font-size: 13px; outline: none;
    transition: border-color 0.15s; font-family: inherit;
}
.form-input:focus { border-color: #2e2e2e; }
.form-input::placeholder { color: #333; }

.btn-add {
    height: 36px; padding: 0 14px;
    background: #378ADD; border: none; border-radius: 7px;
    color: #fff; font-size: 18px; line-height: 1;
    cursor: pointer; transition: background 0.15s; flex-shrink: 0;
    font-family: inherit; font-weight: 400;
}
.btn-add:hover { background: #2e72c9; }
.btn-cancel-edit {
    height: 36px; width: 36px;
    background: none; border: 1px solid #2a2a2a; border-radius: 7px;
    color: #666; cursor: pointer; font-size: 13px;
    flex-shrink: 0; transition: all 0.15s;
}
.btn-cancel-edit:hover { border-color: #444; color: #999; }

.form-error { font-size: 12px; color: #E24B4A; margin-top: -6px; }
.list-empty { font-size: 13px; color: #333; text-align: center; padding: 24px 0; }

.item-list { list-style: none; display: flex; flex-direction: column; gap: 4px; }
.item-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 10px; border-radius: 8px;
    transition: background 0.12s;
}
.item-row:hover { background: #161616; }
.item-dot { width: 10px; height: 10px; border-radius: 50%; flex-shrink: 0; }
.item-name { flex: 1; font-size: 13.5px; color: #c0bebb; }
.item-count { font-size: 11px; color: #3a3a3a; }
.item-actions { display: flex; gap: 4px; opacity: 0; transition: opacity 0.12s; }
.item-row:hover .item-actions { opacity: 1; }

.btn-icon {
    width: 26px; height: 26px;
    background: none; border: 1px solid #2a2a2a; border-radius: 6px;
    color: #666; cursor: pointer; display: flex; align-items: center; justify-content: center;
    transition: all 0.12s;
}
.btn-icon:hover { border-color: #444; color: #999; }
.btn-icon-red:hover { border-color: rgba(226,75,74,0.4); color: #E24B4A; background: rgba(226,75,74,0.06); }

.tags-cloud { display: flex; flex-direction: column; gap: 4px; }
.tag-item {
    display: flex; align-items: center; gap: 8px;
    padding: 6px 10px; border-radius: 8px;
    transition: background 0.12s;
}
.tag-item:hover { background: #161616; }
.tag-name { flex: 1; font-size: 13px; color: #888; }
.tag-count {
    font-size: 11px; padding: 1px 7px;
    background: #1a1a1a; border: 1px solid #2a2a2a;
    border-radius: 99px; color: #444;
}
.tag-item:hover .item-actions { opacity: 1; }
.tag-item .item-actions { opacity: 0; transition: opacity 0.12s; }
</style>
