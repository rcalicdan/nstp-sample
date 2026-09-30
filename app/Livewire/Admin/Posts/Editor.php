<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Posts;

use App\Forms\Posts\PostForm;
use App\Models\Post;
use App\Traits\WithToast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class Editor extends Component
{
    use WithFileUploads;
    use WithToast;

    public PostForm $form;

    public ?Post $post = null;

    public function boot(): void
    {
        if (! isset($this->form)) {
            $this->form = new PostForm($this, 'form');
        }
    }

    public function mount(?Post $post = null): void
    {
        if (! isset($this->form)) {
            $this->form = new PostForm($this, 'form');
        }

        $this->form->tempToken = (string) Str::uuid();

        if ($post && $post->exists) {
            Gate::authorize('update', $post);

            $this->post = $post;
            $this->form->setPost($post);
        } else {
            Gate::authorize('create', Post::class);
            $this->form->scheduled_at = now()->addHour()->format('Y-m-d\TH:i');
        }
    }

    public function updatedFormTitle(): void
    {
        if (! $this->post) {
            $this->form->generateSlug();
        }
    }

    public function save(?string $forceMode = null): void
    {
        if ($forceMode) {
            $this->form->publish_mode = $forceMode;
        }

        if ($this->post) {
            Gate::authorize('update', $this->post);
            if ($this->form->publish_mode !== 'draft') {
                Gate::authorize('publish', $this->post);
            }

            $this->form->update();
            $msg = match ($this->form->publish_mode) {
                'now' => 'Article published to live website!',
                'schedule' => 'Article scheduled successfully!',
                default => 'Draft saved successfully.',
            };
            $this->toast('success', $msg);
        } else {
            Gate::authorize('create', Post::class);
            if ($this->form->publish_mode !== 'draft') {
                Gate::authorize('publish', Post::class);
            }

            $this->form->store();
            $msg = match ($this->form->publish_mode) {
                'now' => 'Article published immediately!',
                'schedule' => 'Article scheduled for release!',
                default => 'Draft created successfully.',
            };
            $this->toast('success', $msg);
        }

        $this->redirect(route('admin.posts.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.posts.editor');
    }
}
