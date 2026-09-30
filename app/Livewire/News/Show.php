<?php

declare(strict_types=1);

namespace App\Livewire\News;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.public')]
class Show extends Component
{
    public Post $post;

    public function mount(string $slug): void
    {
        $post = Post::with('user')->where('slug', $slug)->firstOrFail();

        if (! $post->is_published || ($post->published_at && $post->published_at->isFuture())) {
            if (! auth()->check() || (! auth()->user()->isSuperAdmin() && ! auth()->user()->isAdmin() && ! auth()->user()->isEditor())) {
                abort(404);
            }
        }

        $this->post = $post;
    }

    /**
     * @return Collection<int, Post>
     */
    #[Computed]
    public function recentBulletins(): Collection
    {
        return Post::published()
            ->where('id', '!=', $this->post->id)
            ->pinnedFirst()
            ->take(4)
            ->get()
        ;
    }

    public function render()
    {
        return view('livewire.news.show', [
            'title' => $this->post->title,
        ]);
    }
}
