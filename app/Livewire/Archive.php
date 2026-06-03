<?php

namespace App\Livewire;

use App\Models\Note;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Archive extends Component
{
    public string $search = '';
    public string $category = '';

    protected $queryString = ['search', 'category'];

    public function unarchive(int $id): void
    {
        Note::where('user_id', Auth::id())
            ->findOrFail($id)
            ->update(['is_archived' => false]);

        session()->flash('success', 'Catatan berhasil dipulihkan ke dashboard.');
    }

    public function delete(int $id): void
    {
        Note::where('user_id', Auth::id())
            ->findOrFail($id)
            ->delete();

        session()->flash('success', 'Catatan dipindahkan ke sampah.');
    }

    public function render()
    {
        $notes = Note::where('user_id', Auth::id())
            ->archived()
            ->with(['category', 'tags'])
            ->when($this->search, fn($q) =>
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%")
            )
            ->when($this->category, fn($q) =>
                $q->where('category_id', $this->category)
            )
            ->latest()
            ->get();

        $categories = Category::where('user_id', Auth::id())->get();

        return view('livewire.archive', compact('notes', 'categories'))
            ->layout('components.layouts.app', ['title' => 'Arsip']);
    }
}
