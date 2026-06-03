<?php

namespace App\Livewire\Notes;

use App\Models\Note;
use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class NoteForm extends Component
{
    public ?Note $note = null;

    public string $title = '';
    public string $content = '';
    public ?int $category_id = null;
    public array $selected_tags = [];
    public string $priority = 'medium';
    public string $color_label = '';
    public ?string $deadline = null;
    public bool $is_pinned = false;
    public bool $is_archived = false;

    protected $rules = [
        'title'         => 'required|string|max:255',
        'content'       => 'required|string',
        'category_id'   => 'nullable|exists:categories,id',
        'selected_tags' => 'array',
        'priority'      => 'in:high,medium,low',
        'color_label'   => 'nullable|string|max:20',
        'deadline'      => 'nullable|date',
    ];

    public function mount(?Note $note = null)
    {
        if ($note && $note->exists) {
            $this->note = $note;
            $this->fill($note->only([
                'title', 'content', 'category_id',
                'priority', 'color_label', 'is_pinned', 'is_archived',
            ]));
            $this->deadline      = $note->deadline?->format('Y-m-d');
            $this->selected_tags = $note->tags->pluck('id')->toArray();
        }
    }

    public function save(string $type = 'publish'): void
    {
        $this->validate();

        $data = [
            'user_id'     => Auth::id(),
            'title'       => $this->title,
            'content'     => $this->content,
            'category_id' => $this->category_id,
            'priority'    => $this->priority,
            'color_label' => $this->color_label,
            'deadline'    => $this->deadline,
            'is_pinned'   => $this->is_pinned,
            'is_archived' => $this->is_archived,
        ];

        $note = $this->note
            ? tap($this->note)->update($data)
            : Note::create($data);

        $note->tags()->sync($this->selected_tags);

        session()->flash('success', 'Catatan berhasil disimpan!');
        redirect()->route('dashboard');
    }

    public function saveDraft(): void
    {
        $this->save('draft');
    }

    public function render()
    {
        return view('livewire.notes.note-form', [
            'categories' => Category::where('user_id', Auth::id())->get(),
            'tags'       => Tag::where('user_id', Auth::id())->get(),
        ])->layout('components.layouts.app', ['title' => 'Tambah Catatan']);
    }
}
