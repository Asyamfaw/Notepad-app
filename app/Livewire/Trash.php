<?php

namespace App\Livewire;

use App\Models\Note;
use Livewire\Component;

class Trash extends Component
{
    public string $search = '';
    public string $category = '';
    public bool $showConfirmEmpty = false;

    protected $queryString = ['search', 'category'];

    protected $listeners = [
        'noteRestored'     => '$refresh',
        'noteForceDeleted' => '$refresh',
    ];

    public function getNotes()
    {
        return Note::onlyTrashed()
            ->where('user_id', auth()->id())
            ->with(['category'])
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%")
            )
            ->when($this->category, fn($q) =>
                $q->where('category_id', $this->category)
            )
            ->latest('deleted_at')
            ->get();
    }

    // Restore satu note
    public function restore(int $id): void
    {
        $note = Note::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $note->restore();

        session()->flash('success', "Catatan \"{$note->title}\" berhasil dipulihkan.");
    }

    // Hapus permanen satu note
    public function forceDelete(int $id): void
    {
        $note = Note::onlyTrashed()
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $title = $note->title;
        $note->forceDelete();

        session()->flash('success', "Catatan \"{$title}\" dihapus permanen.");
    }

    // Kosongkan seluruh trash sekaligus
    public function emptyTrash(): void
    {
        Note::onlyTrashed()
            ->where('user_id', auth()->id())
            ->forceDelete();

        $this->showConfirmEmpty = false;

        session()->flash('success', 'Semua catatan di sampah berhasil dihapus permanen.');
    }

    public function render()
    {
        $notes      = $this->getNotes();
        $categories = \App\Models\Category::where('user_id', auth()->id())->get();

        return view('livewire.trash', compact('notes', 'categories'))
            ->layout('components.layouts.app', ['title' => 'Sampah']);
    }
}
