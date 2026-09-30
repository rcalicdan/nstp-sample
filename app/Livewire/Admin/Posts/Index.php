<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Models\Post;
use App\Traits\WithToast;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;
    use WithToast;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $category = '';

    #[Url(except: '')]
    public string $status = '';

    public function mount(): void
    {
        Gate::authorize('viewAny', Post::class);
    }

    public function updated($property): void
    {
        if (\in_array($property, ['search', 'category', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'category', 'status']);
        $this->resetPage();
    }

    public function togglePublish(Post $post): void
    {
        Gate::authorize('publish', $post);

        $newStatus = ! $post->is_published;

        $post->update([
            'is_published' => $newStatus,
            'published_at' => $newStatus ? ($post->published_at ?? now()) : $post->published_at,
        ]);

        $this->toast('success', $newStatus ? 'Article published to live website.' : 'Article reverted to draft.');
    }

    public function togglePin(Post $post): void
    {
        Gate::authorize('update', $post);

        $post->update([
            'is_pinned' => ! $post->is_pinned,
        ]);

        $this->toast('success', $post->is_pinned ? 'Article pinned to top of bulletins.' : 'Article unpinned.');
    }

    public function deletePost(Post $post): void
    {
        Gate::authorize('delete', $post);

        $post->delete();

        $this->toast('success', 'Article and associated media permanently removed.');
    }

    /**
     * @return LengthAwarePaginator<Post>
     */
    #[Computed]
    public function posts(): LengthAwarePaginator
    {
        return Post::with('user')
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('title', 'ilike', '%' . $this->search . '%')
                        ->orWhere('excerpt', 'ilike', '%' . $this->search . '%')
                        ->orWhereHas('user', function (Builder $uq) {
                            $uq->where('first_name', 'ilike', '%' . $this->search . '%')
                                ->orWhere('last_name', 'ilike', '%' . $this->search . '%')
                            ;
                        })
                    ;
                });
            })
            ->when($this->category, fn (Builder $q) => $q->where('category', $this->category))
            ->when($this->status !== '', function (Builder $q) {
                if ($this->status === 'published') {
                    $q->where('is_published', true);
                } elseif ($this->status === 'draft') {
                    $q->where('is_published', false);
                }
            })
            ->orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->paginate(15)
        ;
    }

    public function render()
    {
        return view('livewire.admin.posts.index');
    }
}
