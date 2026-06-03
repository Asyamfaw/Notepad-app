<?php

namespace App\Livewire\Categories;

use App\Models\Category;
use App\Models\Tag;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CategoryList extends Component
{
    // Category
    public string $cat_name = '';
    public string $cat_color = '#378ADD';
    public ?int $editing_cat_id = null;

    // Tag
    public string $tag_name = '';
    public ?int $editing_tag_id = null;

    public function saveCategory(): void
    {
        $this->validate(['cat_name' => 'required|string|max:255']);

        Category::updateOrCreate(
            ['id' => $this->editing_cat_id],
            ['user_id' => Auth::id(), 'name' => $this->cat_name, 'color' => $this->cat_color]
        );

        $this->reset(['cat_name', 'cat_color', 'editing_cat_id']);
        session()->flash('success', 'Kategori berhasil disimpan.');
    }

    public function editCategory(int $id): void
    {
        $cat = Category::where('user_id', Auth::id())->findOrFail($id);
        $this->editing_cat_id = $cat->id;
        $this->cat_name       = $cat->name;
        $this->cat_color      = $cat->color ?? '#378ADD';
    }

    public function deleteCategory(int $id): void
    {
        Category::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Kategori berhasil dihapus.');
    }

    public function saveTag(): void
    {
        $this->validate(['tag_name' => 'required|string|max:255']);

        Tag::updateOrCreate(
            ['id' => $this->editing_tag_id],
            ['user_id' => Auth::id(), 'name' => $this->tag_name]
        );

        $this->reset(['tag_name', 'editing_tag_id']);
        session()->flash('success', 'Tag berhasil disimpan.');
    }

    public function editTag(int $id): void
    {
        $tag = Tag::where('user_id', Auth::id())->findOrFail($id);
        $this->editing_tag_id = $tag->id;
        $this->tag_name       = $tag->name;
    }

    public function deleteTag(int $id): void
    {
        Tag::where('user_id', Auth::id())->findOrFail($id)->delete();
        session()->flash('success', 'Tag berhasil dihapus.');
    }

    public function render()
    {
        return view('livewire.categories.category-list', [
            'categories' => Category::where('user_id', Auth::id())->withCount('notes')->get(),
            'tags'       => Tag::where('user_id', Auth::id())->withCount('notes')->get(),
        ])->layout('components.layouts.app', ['title' => 'Kategori & Tag']);
    }
}
