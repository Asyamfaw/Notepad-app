<div class="form-page">
    <div class="form-header">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Kembali
        </a>
        <h1 class="form-title">{{ $note ? 'Edit Catatan' : 'Catatan Baru' }}</h1>
    </div>

    <form wire:submit.prevent="save" class="note-form">

        {{-- Title --}}
        <div class="form-group">
            <label class="form-label">Judul <span class="required">*</span></label>
            <input type="text" wire:model.defer="title"
                   class="form-input {{ $errors->has('title') ? 'input-error' : '' }}"
                   placeholder="Judul catatan...">
            @error('title') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Content --}}
        <div class="form-group">
            <label class="form-label">Isi Catatan <span class="required">*</span></label>
            <textarea wire:model.defer="content" rows="8"
                      class="form-input form-textarea {{ $errors->has('content') ? 'input-error' : '' }}"
                      placeholder="Tulis catatanmu di sini..."></textarea>
            @error('content') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Row: Category & Priority --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <select wire:model.defer="category_id" class="form-input">
                    <option value="">— Tanpa Kategori —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Prioritas</label>
                <select wire:model.defer="priority" class="form-input">
                    <option value="high">🔴 Tinggi</option>
                    <option value="medium">🟡 Sedang</option>
                    <option value="low">🟢 Rendah</option>
                </select>
            </div>
        </div>

        {{-- Row: Deadline & Color --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Deadline</label>
                <input type="date" wire:model.defer="deadline" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Warna Label</label>
                <div style="display:flex;align-items:center;gap:8px;">
                    <input type="color" wire:model.defer="color_label"
                           style="height:40px;width:48px;border:1px solid #2a2a2a;border-radius:8px;background:#111;cursor:pointer;padding:2px;">
                    <input type="text" wire:model.defer="color_label" class="form-input"
                           placeholder="#378ADD" style="flex:1;">
                </div>
            </div>
        </div>

        {{-- Tags --}}
        @if($tags->count())
        <div class="form-group">
            <label class="form-label">Tag</label>
            <div class="tags-wrap">
                @foreach($tags as $tag)
                    <label class="tag-check {{ in_array($tag->id, $selected_tags) ? 'tag-check--active' : '' }}">
                        <input type="checkbox" wire:model.defer="selected_tags" value="{{ $tag->id }}" style="display:none;">
                        #{{ $tag->name }}
                    </label>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Toggles --}}
        <div class="toggles-row">
            <label class="toggle-label">
                <input type="checkbox" wire:model.defer="is_pinned">
                <span>📌 Pin catatan ini</span>
            </label>
            <label class="toggle-label">
                <input type="checkbox" wire:model.defer="is_archived">
                <span>📦 Langsung arsipkan</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <button type="button" wire:click="saveDraft" class="btn-secondary" wire:loading.attr="disabled">
                Simpan Draft
            </button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ $note ? 'Simpan Perubahan' : 'Publikasikan' }}</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
            </button>
        </div>
    </form>
</div>

<style>
.form-page { max-width: 720px; margin: 0 auto; font-family: 'Inter', sans-serif; }

.form-header { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
.btn-back {
    display: flex; align-items: center; gap: 6px;
    color: #666; font-size: 13px; text-decoration: none;
    transition: color 0.15s;
}
.btn-back:hover { color: #999; }
.form-title { font-size: 20px; font-weight: 500; color: #e8e6e0; letter-spacing: -0.3px; }

.note-form { display: flex; flex-direction: column; gap: 16px; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }

.form-group { display: flex; flex-direction: column; gap: 6px; }
.form-label { font-size: 12.5px; font-weight: 500; color: #888; }
.required { color: #E24B4A; }
.form-input {
    height: 40px; padding: 0 12px;
    background: #111; border: 1px solid #222; border-radius: 8px;
    color: #e0e0e0; font-size: 14px; outline: none;
    transition: border-color 0.15s; font-family: inherit; width: 100%;
}
.form-input:focus { border-color: #2e2e2e; }
.form-textarea { height: auto; padding: 12px; resize: vertical; line-height: 1.7; }
.input-error { border-color: #E24B4A !important; }
.form-error { font-size: 12px; color: #E24B4A; }

.tags-wrap { display: flex; flex-wrap: wrap; gap: 8px; }
.tag-check {
    padding: 5px 12px; border-radius: 99px;
    border: 1px solid #2a2a2a; color: #666;
    font-size: 12px; cursor: pointer;
    transition: all 0.15s;
}
.tag-check:hover { border-color: #378ADD; color: #378ADD; }
.tag-check--active { border-color: #378ADD; color: #378ADD; background: rgba(55,138,221,0.1); }

.toggles-row { display: flex; gap: 20px; flex-wrap: wrap; }
.toggle-label {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; color: #777; cursor: pointer;
}
.toggle-label input { accent-color: #378ADD; }

.form-actions { display: flex; gap: 10px; justify-content: flex-end; padding-top: 8px; }
.btn-primary {
    height: 40px; padding: 0 22px;
    background: #378ADD; border: none; border-radius: 8px;
    color: #fff; font-size: 13.5px; font-weight: 500;
    cursor: pointer; transition: background 0.15s; font-family: inherit;
}
.btn-primary:hover { background: #2e72c9; }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.btn-secondary {
    height: 40px; padding: 0 22px;
    background: none; border: 1px solid #2a2a2a; border-radius: 8px;
    color: #888; font-size: 13.5px;
    cursor: pointer; transition: all 0.15s; font-family: inherit;
}
.btn-secondary:hover { border-color: #444; color: #bbb; }
</style>
