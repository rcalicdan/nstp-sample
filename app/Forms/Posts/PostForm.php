<?php

declare(strict_types=1);

namespace App\Forms\Posts;

use App\Enums\PostCategory;
use App\Models\Post;
use App\Models\PostImage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PostForm extends Form
{
    public ?Post $post = null;

    public string $tempToken = '';

    public string $title = '';

    public string $slug = '';

    public string $category = PostCategory::ANNOUNCEMENT->value;

    public ?string $excerpt = null;

    public string $content = '';

    /**
     * Publication mode: 'now', 'schedule', or 'draft'.
     */
    public string $publish_mode = 'now';

    public ?string $scheduled_at = null;

    public bool $is_pinned = false;

    /**
     * @var \Illuminate\Http\UploadedFile|string|null
     */
    public $featuredImage = null;

    public ?string $existingFeaturedImage = null;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('posts', 'slug')->ignore($this->post?->id)],
            'category' => ['required', Rule::enum(PostCategory::class)],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'publish_mode' => ['required', 'in:now,schedule,draft'],
            'scheduled_at' => [
                'nullable',
                Rule::requiredIf(fn () => $this->publish_mode === 'schedule'),
                'date',
            ],
            'is_pinned' => ['required', 'boolean'],
            'featuredImage' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->category = $post->category->value;
        $this->excerpt = $post->excerpt;
        $this->content = $post->content;
        $this->is_pinned = $post->is_pinned;
        $this->existingFeaturedImage = $post->featured_image_url;

        if (! $post->is_published) {
            $this->publish_mode = 'draft';
        } elseif ($post->published_at && $post->published_at->isFuture()) {
            $this->publish_mode = 'schedule';
            $this->scheduled_at = $post->published_at->format('Y-m-d\TH:i');
        } else {
            $this->publish_mode = 'now';
        }
    }

    public function generateSlug(): void
    {
        $base = Str::slug($this->title) ?: 'bulletin';
        $timestamp = (string) time();

        $this->slug = "{$base}-{$timestamp}";
    }

    public function store(): Post
    {
        if (empty($this->slug)) {
            $this->generateSlug();
        }

        $this->validate();

        $featuredImagePath = null;
        if ($this->featuredImage) {
            $featuredImagePath = $this->featuredImage->store('posts/featured', 'public');
        }

        [$isPublished, $publishedAt] = $this->resolvePublicationTimings();

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $featuredImagePath,
            'is_published' => $isPublished,
            'is_pinned' => $this->is_pinned,
            'published_at' => $publishedAt,
        ]);

        PostImage::where('temp_token', $this->tempToken)->update([
            'post_id' => $post->id,
            'temp_token' => null,
        ]);

        return $post;
    }

    public function update(): Post
    {
        $this->validate();

        $featuredImagePath = $this->post->featured_image;
        if ($this->featuredImage) {
            if ($featuredImagePath && Storage::disk('public')->exists($featuredImagePath)) {
                Storage::disk('public')->delete($featuredImagePath);
            }
            $featuredImagePath = $this->featuredImage->store('posts/featured', 'public');
        }

        [$isPublished, $publishedAt] = $this->resolvePublicationTimings();

        $this->post->update([
            'title' => $this->title,
            'slug' => $this->slug,
            'category' => $this->category,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'featured_image' => $featuredImagePath,
            'is_published' => $isPublished,
            'is_pinned' => $this->is_pinned,
            'published_at' => $publishedAt,
        ]);

        PostImage::where('temp_token', $this->tempToken)->update([
            'post_id' => $this->post->id,
            'temp_token' => null,
        ]);

        return $this->post;
    }

    public function removeFeaturedImage(): void
    {
        if ($this->post && $this->post->featured_image) {
            Storage::disk('public')->delete($this->post->featured_image);
            $this->post->update(['featured_image' => null]);
        }

        $this->featuredImage = null;
        $this->existingFeaturedImage = null;
    }

    /**
     * @return array{0: bool, 1: ?Carbon}
     */
    private function resolvePublicationTimings(): array
    {
        return match ($this->publish_mode) {
            'now' => [true, now()],
            'schedule' => [true, Carbon::parse($this->scheduled_at)],
            'draft' => [false, null],
            default => [false, null],
        };
    }
}
