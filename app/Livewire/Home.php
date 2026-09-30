<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\PostCategory;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Home extends Component
{
    public string $selectedCategory = '';

    public function setCategory(string $category): void
    {
        $this->selectedCategory = $category;
    }

    /**
     * @return Collection<int, Post>
     */
    #[Computed]
    public function bulletins(): Collection
    {
        return Post::published()
            ->when($this->selectedCategory, fn ($q) => $q->where('category', $this->selectedCategory))
            ->pinnedFirst()
            ->take(6)
            ->get()
        ;
    }

    /**
     * @return ?Post
     */
    #[Computed]
    public function featuredPinned(): ?Post
    {
        return Post::published()
            ->where('is_pinned', true)
            ->latest('published_at')
            ->first()
        ;
    }

    public function render()
    {
        return view('livewire.home', [
            'title' => 'Home',
            'categories' => PostCategory::cases(),
        ]);
    }
}
