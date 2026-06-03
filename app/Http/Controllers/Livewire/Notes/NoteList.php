<?php

namespace App\Http\Livewire\Notes;

use App\Models\Note;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NoteList extends Component
{
    public string $search = '';
    public string $category = '';
    public string $priority = '';

    protected $queryString = ['search', 'category', 'priority'];

    public function archive(int $id): void
    {
        Note::where('user_id', Auth::id())
            ->findOrFail($id)
            ->update(['is_archived' => true]);
    }

    public function togglePin(int $id): void
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->update(['is_pinned' => !$note->is_pinned]);
    }

    public function delete(int $id): void
    {
        Note::where('user_id', Auth::id())
            ->findOrFail($id)
            ->delete();
    }

    public function render()
    {
        $notes = Note::where('user_id', Auth::id())
            ->active()
            ->with(['category', 'tags'])
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%")
            )
            ->when($this->category, fn($q) =>
                $q->where('category_id', $this->category)
            )
            ->when($this->priority, fn($q) =>
                $q->where('priority', $this->priority)
            )
            ->pinnedFirst()
            ->get();

        $categories = Category::where('user_id', Auth::id())->get();

        return view('components.notes.note-list', compact('notes', 'categories'))
            ->layout('layouts.app', ['title' => 'Dashboard']);
    }
}