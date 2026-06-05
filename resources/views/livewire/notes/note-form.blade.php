<div class="form-page">
    <div class="form-header">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 5l-7 7 7 7"/>
            </svg>
            Back to Dashboard
        </a>
        <h1 class="form-title">{{ $note ? 'Edit Note' : 'Create New Note' }}</h1>
    </div>

    <form wire:submit.prevent="save" class="note-form">

        {{-- Title --}}
        <div class="form-group">
            <label class="form-label">Title <span class="required">*</span></label>
            <input type="text" wire:model.defer="title"
                   class="form-input {{ $errors->has('title') ? 'input-error' : '' }}"
                   placeholder="Write your note title...">
            @error('title') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Content --}}
        <div class="form-group">
            <label class="form-label">Content <span class="required">*</span></label>
            <textarea wire:model.defer="content" rows="10"
                      class="form-input form-textarea {{ $errors->has('content') ? 'input-error' : '' }}"
                      placeholder="Write your thoughts here..."></textarea>
            @error('content') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        {{-- Row: Category & Priority --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Category</label>
                <select wire:model.defer="category_id" class="form-input">
                    <option value="">— No Category —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Priority</label>
                <select wire:model.defer="priority" class="form-input">
                    <option value="high">🔴 High</option>
                    <option value="medium">🟡 Medium</option>
                    <option value="low">🟢 Low</option>
                </select>
            </div>
        </div>

        {{-- Row: Deadline & Color Label --}}
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Deadline</label>
                <input type="date" wire:model.defer="deadline" class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Color Label</label>
                <div class="color-group">
                    <input type="color" wire:model.defer="color_label" class="color-input">
                    <input type="text" wire:model.defer="color_label" class="form-input color-text"
                           placeholder="#23A9BD">
                </div>
            </div>
        </div>

        {{-- Tags Selection --}}
        @if($tags->count())
        <div class="form-group">
            <label class="form-label">Tags (multi-select)</label>
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

        {{-- Toggle Options --}}
        <div class="toggles-row">
            <label class="toggle-label">
                <input type="checkbox" wire:model.defer="is_pinned">
                <span>📌 Pin this note</span>
            </label>
            <label class="toggle-label">
                <input type="checkbox" wire:model.defer="is_archived">
                <span>📦 Archive immediately</span>
            </label>
        </div>

        {{-- Form Actions --}}
        <div class="form-actions">
            <button type="button" wire:click="saveDraft" class="btn-secondary" wire:loading.attr="disabled">
                Save as Draft
            </button>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="save">{{ $note ? 'Save Changes' : 'Publish Note' }}</span>
                <span wire:loading wire:target="save">Saving...</span>
            </button>
        </div>
    </form>
</div>

<style>
.form-page { max-width: 720px; margin: 0 auto; padding: 2rem; background: linear-gradient(135deg, #0A0C0F 0%, #0D1117 100%); min-height: 100vh; }

.form-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
.btn-back {
    display: inline-flex; align-items: center; gap: 0.5rem;
    color: #94A3B8; font-size: 0.875rem; text-decoration: none;
    transition: all 0.2s; padding: 0.5rem 1rem; border-radius: 10px;
    background: rgba(13, 78, 89, 0.05); border: 1px solid rgba(35, 169, 189, 0.15);
}
.btn-back:hover { color: #23A9BD; border-color: rgba(35, 169, 189, 0.3); }
.form-title { font-size: 1.5rem; font-weight: 600; background: linear-gradient(135deg, #23A9BD 0%, #0D4E59 100%); -webkit-background-clip: text; background-clip: text; color: transparent; letter-spacing: -0.5px; }

.note-form { display: flex; flex-direction: column; gap: 1.25rem; }
.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }

.form-group { display: flex; flex-direction: column; gap: 0.5rem; }
.form-label { font-size: 0.75rem; font-weight: 600; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px; }
.required { color: #DA8642; }
.form-input {
    height: 44px; padding: 0 1rem;
    background: #0D1117; border: 1px solid rgba(35, 169, 189, 0.2); border-radius: 10px;
    color: #e0e0e0; font-size: 0.875rem; outline: none; transition: all 0.2s;
    width: 100%;
}
.form-input:focus { border-color: #23A9BD; box-shadow: 0 0 0 2px rgba(35, 169, 189, 0.1); }
.form-textarea { height: auto; padding: 1rem; resize: vertical; line-height: 1.6; }
.input-error { border-color: #DA8642 !important; }
.form-error { font-size: 0.688rem; color: #DA8642; }

.color-group { display: flex; gap: 0.5rem; align-items: center; }
.color-input { width: 48px; height: 44px; border: 1px solid rgba(35, 169, 189, 0.2); border-radius: 10px; background: #0D1117; cursor: pointer; }
.color-text { flex: 1; }

.tags-wrap { display: flex; flex-wrap: wrap; gap: 0.5rem; }
.tag-check {
    padding: 0.375rem 1rem; border-radius: 99px;
    border: 1px solid rgba(35, 169, 189, 0.25); color: #94A3B8;
    font-size: 0.75rem; cursor: pointer; transition: all 0.2s;
}
.tag-check:hover { border-color: #23A9BD; color: #23A9BD; }
.tag-check--active { border-color: #23A9BD; color: #23A9BD; background: rgba(35, 169, 189, 0.1); }

.toggles-row { display: flex; gap: 1.5rem; flex-wrap: wrap; padding: 0.5rem 0; }
.toggle-label {
    display: flex; align-items: center; gap: 0.5rem;
    font-size: 0.813rem; color: #94A3B8; cursor: pointer;
}
.toggle-label input { accent-color: #23A9BD; width: 18px; height: 18px; }

.form-actions { display: flex; gap: 1rem; justify-content: flex-end; padding-top: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.05); margin-top: 0.5rem; }
.btn-primary {
    height: 44px; padding: 0 1.75rem;
    background: #23A9BD; border: none; border-radius: 10px;
    color: #fff; font-size: 0.875rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s;
}
.btn-primary:hover { background: #1D8FA0; transform: translateY(-1px); }
.btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
.btn-secondary {
    height: 44px; padding: 0 1.75rem;
    background: rgba(13, 78, 89, 0.05); border: 1px solid rgba(35, 169, 189, 0.25); border-radius: 10px;
    color: #94A3B8; font-size: 0.875rem; font-weight: 500;
    cursor: pointer; transition: all 0.2s;
}
.btn-secondary:hover { border-color: #23A9BD; color: #23A9BD; }

@media (max-width: 640px) {
    .form-page { padding: 1rem; }
    .form-row { grid-template-columns: 1fr; gap: 1rem; }
    .form-actions { flex-direction: column-reverse; }
    .btn-primary, .btn-secondary { width: 100%; }
}
</style>