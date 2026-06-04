<div class="cat-page">

    {{-- ── Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Noteku</h1>
            <p class="page-sub">Taxonomy & Organization — Manage your categories and tags for deep focus</p>
        </div>
        <div class="header-stats">
            <span class="stat-badge">📊 Categorized Notes: 82%</span>
            <span class="stat-badge">📈 +72% last month</span>
        </div>
    </div>

    {{-- ── Flash Message ── --}}
    @if(session()->has('success'))
        <div class="flash-success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M20 6L9 17l-5-5"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="flash-error" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="two-col">

        {{-- ──────── KATEGORI SECTION ──────── --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-header-left">
                    <svg class="panel-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <h2 class="panel-title">Active Categories</h2>
                </div>
                <span class="panel-count">{{ $categories->count() }} total</span>
            </div>

            {{-- Form Tambah/Edit Kategori --}}
            <form wire:submit.prevent="saveCategory" class="add-form" id="categoryForm">
                <input type="text" 
                       wire:model.defer="cat_name"
                       id="cat_name"
                       class="form-input" 
                       placeholder="New category name...">
                <input type="color" 
                       wire:model.defer="cat_color"
                       id="cat_color"
                       title="Choose color"
                       class="color-picker">
                <button type="submit" class="btn-add" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editing_cat_id ? 'Save' : '+' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
                @if($editing_cat_id)
                    <button type="button" 
                            wire:click="$set('editing_cat_id', null)" 
                            class="btn-cancel-edit"
                            onclick="cancelEditCategoryConfirm()">
                        ✕ Cancel
                    </button>
                @endif
            </form>
            @error('cat_name') <p class="form-error">{{ $message }}</p> @enderror

            {{-- List Kategori --}}
            @if($categories->isEmpty())
                <div class="empty-list">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M20 7h-3a2 2 0 01-2-2V3a2 2 0 00-2-2H7a2 2 0 00-2 2v16a2 2 0 002 2h10a2 2 0 002-2v-8"/>
                        <path d="M16 3h3a2 2 0 012 2v3"/>
                    </svg>
                    <p>✨ No categories yet. Create your first one!</p>
                </div>
            @else
                <ul class="item-list">
                    @foreach($categories as $cat)
                        <li class="item-row" wire:key="cat-{{ $cat->id }}">
                            <div class="item-dot" style="background: {{ $cat->color ?? '#23A9BD' }}"></div>
                            <div class="item-info">
                                <span class="item-name">{{ $cat->name }}</span>
                                <span class="item-count">{{ $cat->notes_count }} note(s)</span>
                            </div>
                            <div class="item-actions">
                                <button wire:click="editCategory({{ $cat->id }})" 
                                        class="btn-icon btn-edit-cat" 
                                        title="Edit Category">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteCategory({{ $cat->id }}, '{{ addslashes($cat->name) }}', {{ $cat->notes_count }})" 
                                        class="btn-icon btn-delete-cat" 
                                        title="Delete Category">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/>
                                        <path d="M19 6l-1 14H6L5 6"/>
                                    </svg>
                                </button>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif

            {{-- Quick Add Entry --}}
            <div class="quick-add">
                <div class="quick-add-header">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <span>Quick Add Entry</span>
                </div>
                <div class="quick-add-tags">
                    <span class="quick-tag">#work</span>
                    <span class="quick-tag">#personal</span>
                    <span class="quick-tag">#ideas</span>
                    <span class="quick-tag">#meeting</span>
                </div>
            </div>
        </div>

        {{-- ──────── TAG SECTION ──────── --}}
        <div class="panel">
            <div class="panel-header">
                <div class="panel-header-left">
                    <svg class="panel-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A2 2 0 013 13V5a2 2 0 012-2z"/>
                    </svg>
                    <h2 class="panel-title">Global Tags Cloud</h2>
                </div>
                <span class="panel-count">{{ $tags->count() }} total</span>
            </div>

            {{-- Form Tambah/Edit Tag --}}
            <form wire:submit.prevent="saveTag" class="add-form" id="tagForm">
                <input type="text" 
                       wire:model.defer="tag_name"
                       id="tag_name"
                       class="form-input" 
                       placeholder="New tag name...">
                <button type="submit" class="btn-add" wire:loading.attr="disabled">
                    <span wire:loading.remove>{{ $editing_tag_id ? 'Save' : '+' }}</span>
                    <span wire:loading>Saving...</span>
                </button>
                @if($editing_tag_id)
                    <button type="button" 
                            wire:click="$set('editing_tag_id', null)" 
                            class="btn-cancel-edit"
                            onclick="cancelEditTagConfirm()">
                        ✕ Cancel
                    </button>
                @endif
            </form>
            @error('tag_name') <p class="form-error">{{ $message }}</p> @enderror

            {{-- Tags Cloud --}}
            @if($tags->isEmpty())
                <div class="empty-list">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                        <line x1="7" y1="7" x2="7.01" y2="7"/>
                    </svg>
                    <p>🏷️ No tags yet. Add some to organize better!</p>
                </div>
            @else
                <div class="tags-cloud">
                    @foreach($tags as $tag)
                        <div class="tag-item" wire:key="tag-{{ $tag->id }}">
                            <span class="tag-hash">#</span>
                            <span class="tag-name">{{ $tag->name }}</span>
                            <span class="tag-count">{{ $tag->notes_count }}</span>
                            <div class="tag-actions">
                                <button wire:click="editTag({{ $tag->id }})" 
                                        class="btn-icon btn-edit-tag" 
                                        title="Edit Tag">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                        <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                    </svg>
                                </button>
                                <button onclick="confirmDeleteTag({{ $tag->id }}, '{{ addslashes($tag->name) }}', {{ $tag->notes_count }})" 
                                        class="btn-icon btn-delete-tag" 
                                        title="Delete Tag">
                                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Workspace Health --}}
            <div class="workspace-health">
                <div class="health-header">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                    </svg>
                    <span>Workspace Health</span>
                </div>
                <div class="health-bar">
                    <div class="health-progress" style="width: 72%"></div>
                </div>
                <div class="health-stats">
                    <span>Organization increased by 72%</span>
                    <span>Last 30 days</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Footer ── --}}
    <div class="page-footer">
        <p>© 2024 Noteku. Designed for deep work. Premium Workspace</p>
        <div class="footer-links">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
            <a href="#">Contact</a>
            <a href="#">Twitter</a>
        </div>
    </div>
</div>

{{-- SweetAlert2 CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // ==================== CATEGORY FUNCTIONS ====================
    
    // Delete Category dengan SweetAlert (menggunakan method deleteCategory yang sudah ada)
    function confirmDeleteCategory(catId, catName, notesCount) {
        const hasNotes = notesCount > 0;
        const warningMessage = hasNotes 
            ? `<span style="color: #DA8642;">⚠️ This category contains ${notesCount} note(s).</span><br>Notes will become uncategorized after deletion.`
            : `This category has no notes. It can be safely deleted.`;
        
        Swal.fire({
            title: 'Delete Category?',
            html: `
                <div style="text-align: left;">
                    <p>Are you sure you want to delete <strong>"${catName}"</strong>?</p>
                    <p style="font-size: 0.875rem; color: #DA8642; margin-top: 0.5rem;">${warningMessage}</p>
                </div>
            `,
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
                title: 'swal-title'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Menggunakan method deleteCategory yang sudah ada di Livewire
                @this.call('deleteCategory', catId);
                
                Swal.fire({
                    title: 'Deleted!',
                    text: hasNotes 
                        ? `Category "${catName}" has been deleted. ${notesCount} note(s) are now uncategorized.`
                        : `Category "${catName}" has been deleted.`,
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
    
    // Cancel Edit Category Confirmation
    function cancelEditCategoryConfirm() {
        Swal.fire({
            title: 'Cancel Editing?',
            text: 'Your changes will be discarded.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#DA8642',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, cancel',
            cancelButtonText: 'Continue Editing',
            background: '#0D1117',
            color: '#e0e0e0',
            customClass: {
                popup: 'swal-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.set('editing_cat_id', null);
                @this.set('cat_name', '');
                @this.set('cat_color', '#23A9BD');
            }
        });
    }
    
    // ==================== TAG FUNCTIONS ====================
    
    // Delete Tag dengan SweetAlert (menggunakan method deleteTag yang sudah ada)
    function confirmDeleteTag(tagId, tagName, notesCount) {
        const hasNotes = notesCount > 0;
        const warningMessage = hasNotes 
            ? `<span style="color: #DA8642;">⚠️ This tag is used on ${notesCount} note(s).</span><br>The tag will be removed from all notes.`
            : `This tag is not used on any notes.`;
        
        Swal.fire({
            title: 'Delete Tag?',
            html: `
                <div style="text-align: left;">
                    <p>Are you sure you want to delete <strong style="color: #23A9BD;">#${tagName}</strong>?</p>
                    <p style="font-size: 0.875rem; color: #DA8642; margin-top: 0.5rem;">${warningMessage}</p>
                </div>
            `,
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
                title: 'swal-title'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Menggunakan method deleteTag yang sudah ada di Livewire
                @this.call('deleteTag', tagId);
                
                Swal.fire({
                    title: 'Deleted!',
                    text: hasNotes 
                        ? `Tag "#${tagName}" has been deleted from ${notesCount} note(s).`
                        : `Tag "#${tagName}" has been deleted.`,
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
    
    // Cancel Edit Tag Confirmation
    function cancelEditTagConfirm() {
        Swal.fire({
            title: 'Cancel Editing?',
            text: 'Your changes will be discarded.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#DA8642',
            cancelButtonColor: '#94A3B8',
            confirmButtonText: 'Yes, cancel',
            cancelButtonText: 'Continue Editing',
            background: '#0D1117',
            color: '#e0e0e0',
            customClass: {
                popup: 'swal-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.set('editing_tag_id', null);
                @this.set('tag_name', '');
            }
        });
    }
    
    // ==================== SUCCESS TOAST NOTIFICATION ====================
    
    // Listen untuk Livewire events
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('category-saved', () => {
            Swal.fire({
                title: 'Success!',
                text: 'Category has been saved.',
                icon: 'success',
                confirmButtonColor: '#23A9BD',
                background: '#0D1117',
                color: '#e0e0e0',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
        
        Livewire.on('tag-saved', () => {
            Swal.fire({
                title: 'Success!',
                text: 'Tag has been saved.',
                icon: 'success',
                confirmButtonColor: '#23A9BD',
                background: '#0D1117',
                color: '#e0e0e0',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    });
    
    // Intercept form submit untuk show loading
    document.addEventListener('DOMContentLoaded', function() {
        const categoryForm = document.getElementById('categoryForm');
        if (categoryForm) {
            categoryForm.addEventListener('submit', function() {
                const btn = this.querySelector('.btn-add');
                if (btn && btn.innerText !== 'Save') {
                    // Show toast for add category
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Saving...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: '#0D1117',
                            color: '#e0e0e0'
                        });
                    }, 100);
                }
            });
        }
        
        const tagForm = document.getElementById('tagForm');
        if (tagForm) {
            tagForm.addEventListener('submit', function() {
                const btn = this.querySelector('.btn-add');
                if (btn && btn.innerText !== 'Save') {
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Saving...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            background: '#0D1117',
                            color: '#e0e0e0'
                        });
                    }, 100);
                }
            });
        }
    });
</script>

<style>
.cat-page { 
    font-family: 'Inter', sans-serif; 
    max-width: 1400px; 
    margin: 0 auto; 
    padding: 2rem; 
    background: linear-gradient(135deg, #0A0C0F 0%, #0D1117 100%); 
    min-height: 100vh; 
}

/* Header */
.page-header { 
    display: flex; 
    justify-content: space-between; 
    align-items: flex-end; 
    margin-bottom: 2rem; 
    flex-wrap: wrap; 
    gap: 1rem; 
}
.page-title { 
    font-size: 2rem; 
    font-weight: 700; 
    background: linear-gradient(135deg, #23A9BD 0%, #0D4E59 100%); 
    -webkit-background-clip: text; 
    background-clip: text; 
    color: transparent; 
    letter-spacing: -0.5px; 
}
.page-sub { 
    font-size: 0.875rem; 
    color: #94A3B8; 
    margin-top: 0.25rem; 
}
.header-stats { 
    display: flex; 
    gap: 0.75rem; 
}
.stat-badge { 
    font-size: 0.75rem; 
    padding: 0.375rem 0.875rem; 
    background: rgba(35, 169, 189, 0.1); 
    border: 1px solid rgba(35, 169, 189, 0.2); 
    border-radius: 99px; 
    color: #23A9BD; 
    font-weight: 500; 
}

/* Flash Messages */
.flash-success {
    display: flex; 
    align-items: center; 
    gap: 0.5rem;
    background: rgba(35, 169, 189, 0.1); 
    border: 1px solid rgba(35, 169, 189, 0.25);
    color: #23A9BD; 
    font-size: 0.875rem; 
    padding: 0.75rem 1rem;
    border-radius: 12px; 
    margin-bottom: 1.5rem;
}
.flash-error {
    display: flex; 
    align-items: center; 
    gap: 0.5rem;
    background: rgba(218, 134, 66, 0.1); 
    border: 1px solid rgba(218, 134, 66, 0.25);
    color: #DA8642; 
    font-size: 0.875rem; 
    padding: 0.75rem 1rem;
    border-radius: 12px; 
    margin-bottom: 1.5rem;
}

/* Two Column Layout */
.two-col { 
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 1.5rem; 
}

/* Panel */
.panel {
    background: rgba(13, 78, 89, 0.05); 
    backdrop-filter: blur(10px);
    border: 1px solid rgba(35, 169, 189, 0.15); 
    border-radius: 1rem;
    padding: 1.5rem; 
    transition: all 0.3s ease;
}
.panel:hover { 
    border-color: rgba(35, 169, 189, 0.3); 
}

.panel-header { 
    display: flex; 
    align-items: center; 
    justify-content: space-between; 
    margin-bottom: 1.25rem; 
}
.panel-header-left {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.panel-icon {
    color: #23A9BD;
}
.panel-title { 
    font-size: 1rem; 
    font-weight: 600; 
    color: #ffffff; 
    letter-spacing: -0.3px; 
}
.panel-count {
    font-size: 0.7rem; 
    padding: 0.25rem 0.625rem;
    background: rgba(35, 169, 189, 0.15); 
    border: 1px solid rgba(35, 169, 189, 0.3);
    border-radius: 99px; 
    color: #23A9BD;
}

/* Form */
.add-form { 
    display: flex; 
    gap: 0.5rem; 
    align-items: center; 
    margin-bottom: 1.25rem; 
}
.form-input {
    flex: 1; 
    height: 42px; 
    padding: 0 1rem;
    background: #0D1117; 
    border: 1px solid rgba(35, 169, 189, 0.2); 
    border-radius: 10px;
    color: #e0e0e0; 
    font-size: 0.875rem; 
    outline: none; 
    transition: all 0.2s;
}
.form-input:focus { 
    border-color: #23A9BD; 
    box-shadow: 0 0 0 2px rgba(35, 169, 189, 0.1); 
}
.form-input::placeholder { 
    color: #94A3B8; 
}

.color-picker { 
    height: 42px; 
    width: 50px; 
    border: 1px solid rgba(35, 169, 189, 0.2); 
    border-radius: 10px; 
    background: #0D1117; 
    cursor: pointer; 
}

.btn-add {
    height: 42px; 
    padding: 0 1.25rem;
    background: #23A9BD; 
    border: none; 
    border-radius: 10px;
    color: #fff; 
    font-size: 1rem; 
    font-weight: 600;
    cursor: pointer; 
    transition: all 0.2s;
}
.btn-add:hover { 
    background: #1D8FA0; 
    transform: translateY(-1px); 
}
.btn-cancel-edit {
    height: 42px; 
    padding: 0 1rem;
    background: rgba(218, 134, 66, 0.1); 
    border: 1px solid rgba(218, 134, 66, 0.3);
    border-radius: 10px; 
    color: #DA8642; 
    cursor: pointer;
    transition: all 0.2s;
}
.btn-cancel-edit:hover { 
    background: rgba(218, 134, 66, 0.2); 
}

.form-error { 
    font-size: 0.7rem; 
    color: #DA8642; 
    margin-top: -0.75rem;
    margin-bottom: 0.75rem;
}

/* Empty List */
.empty-list {
    text-align: center;
    padding: 2.5rem 1rem;
    color: #94A3B8;
}
.empty-list svg {
    color: #23A9BD;
    opacity: 0.5;
    margin-bottom: 0.75rem;
}
.empty-list p {
    font-size: 0.875rem;
}

/* Item List (Categories) */
.item-list { 
    list-style: none; 
    display: flex; 
    flex-direction: column; 
    gap: 0.25rem; 
    max-height: 320px; 
    overflow-y: auto; 
    margin-bottom: 1rem;
}
.item-row {
    display: flex; 
    align-items: center; 
    gap: 0.75rem;
    padding: 0.75rem; 
    border-radius: 10px; 
    transition: all 0.2s;
}
.item-row:hover { 
    background: rgba(255, 255, 255, 0.05); 
}
.item-dot { 
    width: 10px; 
    height: 10px; 
    border-radius: 50%; 
    flex-shrink: 0; 
}
.item-info {
    flex: 1;
    display: flex;
    align-items: baseline;
    gap: 0.5rem;
    flex-wrap: wrap;
}
.item-name { 
    font-size: 0.875rem; 
    color: #e0e0e0; 
    font-weight: 500; 
}
.item-count { 
    font-size: 0.688rem; 
    color: #94A3B8; 
}
.item-actions { 
    display: flex; 
    gap: 0.25rem; 
    opacity: 0; 
    transition: opacity 0.2s; 
}
.item-row:hover .item-actions { 
    opacity: 1; 
}

/* Buttons */
.btn-icon {
    width: 30px; 
    height: 30px;
    background: rgba(255, 255, 255, 0.05); 
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 8px; 
    color: #94A3B8; 
    cursor: pointer;
    display: flex; 
    align-items: center; 
    justify-content: center;
    transition: all 0.2s;
}
.btn-edit-cat:hover { 
    border-color: #23A9BD; 
    color: #23A9BD; 
    background: rgba(35, 169, 189, 0.1); 
}
.btn-delete-cat:hover,
.btn-delete-tag:hover { 
    border-color: #DA8642; 
    color: #DA8642; 
    background: rgba(218, 134, 66, 0.1); 
}

/* Tags Cloud */
.tags-cloud { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 0.625rem; 
    max-height: 280px; 
    overflow-y: auto; 
    padding: 0.25rem; 
    margin-bottom: 1rem;
}
.tag-item {
    display: inline-flex; 
    align-items: center; 
    gap: 0.375rem;
    padding: 0.375rem 0.75rem; 
    border-radius: 99px;
    background: rgba(35, 169, 189, 0.08); 
    border: 1px solid rgba(35, 169, 189, 0.15);
    transition: all 0.2s;
}
.tag-item:hover { 
    background: rgba(35, 169, 189, 0.15); 
    border-color: rgba(35, 169, 189, 0.3); 
}
.tag-hash {
    color: #23A9BD;
    font-weight: 600;
    font-size: 0.75rem;
}
.tag-name { 
    font-size: 0.813rem; 
    color: #e0e0e0; 
    font-weight: 500; 
}
.tag-count { 
    font-size: 0.688rem; 
    color: #94A3B8; 
}
.tag-actions {
    display: inline-flex;
    gap: 0.25rem;
    opacity: 0;
    transition: opacity 0.2s;
    margin-left: 0.25rem;
}
.tag-item:hover .tag-actions { 
    opacity: 1; 
}
.btn-edit-tag {
    width: 24px;
    height: 24px;
}
.btn-delete-tag {
    width: 24px;
    height: 24px;
}
.btn-edit-tag svg,
.btn-delete-tag svg {
    width: 10px;
    height: 10px;
}

/* Quick Add */
.quick-add {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}
.quick-add-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.688rem;
    font-weight: 600;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}
.quick-add-header svg {
    color: #23A9BD;
}
.quick-add-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.quick-tag {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    background: rgba(35, 169, 189, 0.08);
    border: 1px solid rgba(35, 169, 189, 0.15);
    border-radius: 99px;
    color: #94A3B8;
    cursor: pointer;
    transition: all 0.2s;
}
.quick-tag:hover {
    border-color: #23A9BD;
    color: #23A9BD;
}

/* Workspace Health */
.workspace-health {
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}
.health-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.688rem;
    font-weight: 600;
    color: #94A3B8;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}
.health-header svg {
    color: #23A9BD;
}
.health-bar {
    height: 6px;
    background: rgba(35, 169, 189, 0.15);
    border-radius: 3px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}
.health-progress {
    height: 100%;
    background: linear-gradient(90deg, #23A9BD, #0D4E59);
    border-radius: 3px;
    transition: width 0.5s ease;
}
.health-stats {
    display: flex;
    justify-content: space-between;
    font-size: 0.688rem;
    color: #94A3B8;
}

/* Footer */
.page-footer {
    margin-top: 2rem;
    padding-top: 1.5rem;
    text-align: center;
    border-top: 1px solid rgba(255, 255, 255, 0.05);
}
.page-footer p {
    font-size: 0.75rem;
    color: #94A3B8;
    margin-bottom: 0.5rem;
}
.footer-links {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    flex-wrap: wrap;
}
.footer-links a {
    font-size: 0.688rem;
    color: #94A3B8;
    text-decoration: none;
    transition: color 0.2s;
}
.footer-links a:hover {
    color: #23A9BD;
}

/* SweetAlert Custom Styles */
.swal-dark {
    background: #0D1117 !important;
    border: 1px solid rgba(35, 169, 189, 0.3) !important;
    border-radius: 1rem !important;
}
.swal-title {
    color: #ffffff !important;
    font-family: 'Inter', sans-serif !important;
}
.swal-text {
    color: #94A3B8 !important;
    font-family: 'Inter', sans-serif !important;
}
.swal2-actions button {
    font-family: 'Inter', sans-serif !important;
}

/* Responsive */
@media (max-width: 768px) {
    .cat-page { padding: 1rem; }
    .two-col { grid-template-columns: 1fr; }
    .page-header { flex-direction: column; align-items: flex-start; }
    .header-stats { flex-wrap: wrap; }
}
</style>