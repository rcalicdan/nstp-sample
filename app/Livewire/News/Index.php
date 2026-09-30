<?php

declare(strict_types=1);

namespace App\Livewire\News;

use App\Enums\PostCategory;
use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.public')]
class Index extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $category = '';

    public function updated($property): void
    {
        if (in_array($property, ['search', 'category'], true)) {
            $this->resetPage();
        }
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category']);
        $this->resetPage();
    }

    /**
     * @return LengthAwarePaginator<Post>
     */
    #[Computed]
    public function posts(): LengthAwarePaginator
    {
        return Post::published()
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('title', 'ilike', '%' . $this->search . '%')
                        ->orWhere('excerpt', 'ilike', '%' . $this->search . '%')
                        ->orWhere('content', 'ilike', '%' . $this->search . '%');
                });
            })
            ->when($this->category, fn (Builder $q) => $q->where('category', $this->category))
            ->pinnedFirst()
            ->paginate(9);
    }

    public function render()
    {
        return view('livewire.news.index', [
            'title' => 'Official Bulletins & Advisories',
            'categories' => PostCategory::cases(),
        ]);
    }
}