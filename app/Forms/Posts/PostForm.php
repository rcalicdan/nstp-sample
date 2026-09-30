<?php

declare(strict_types=1);

namespace App\Forms\Posts;

use App\Enums\PostCategory;
use App\Models\Post;
use App\Models\PostImage;
use Dom\HTMLDocument;
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

    public string $publish_mode = 'now';

    public ?string $scheduled_at = null;

    public bool $is_pinned = false;

    /** @var \Illuminate\Http\UploadedFile|string|null */
    public $featuredImage = null;

    public ?string $existingFeaturedImage = null;

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
                Rule::requiredIf(fn() => $this->publish_mode === 'schedule'),
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

        $this->syncInlineImages($post);

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

        $this->syncInlineImages($this->post);

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

    private function syncInlineImages(Post $post): void
    {
        if (empty($this->content)) {
            return;
        }

        $document = HTMLDocument::createFromString($this->content, LIBXML_NOERROR);
        $imgNodes = $document->querySelectorAll('img');

        $activePaths = [];

        foreach ($imgNodes as $img) {
            $src = $img->getAttribute('src') ?? '';

            if (! str_contains($src, '/storage/')) {
                continue;
            }

            $relativePath = ltrim(parse_url($src, PHP_URL_PATH) ?? '', '/');
            $storagePrefix = 'storage/';
            if (str_starts_with($relativePath, $storagePrefix)) {
                $relativePath = substr($relativePath, strlen($storagePrefix));
            }

            $activePaths[] = $relativePath;

            $width = $img->getAttribute('data-width') ?: '100%';
            $alignment = $img->getAttribute('data-alignment') ?: 'center';
            $caption = $img->getAttribute('data-caption');

            if (empty($caption) && strtolower($img->parentElement?->tagName ?? '') === 'figure') {
                $figcaption = $img->parentElement->querySelector('figcaption');
                if ($figcaption) {
                    $caption = trim($figcaption->textContent);
                }
            }

            PostImage::where('file_path', $relativePath)
                ->where(function ($q) use ($post) {
                    $q->where('post_id', $post->id)
                        ->orWhere('temp_token', $this->tempToken);
                })
                ->update([
                    'post_id' => $post->id,
                    'temp_token' => null,
                    'width' => $width,
                    'alignment' => $alignment,
                    'caption' => ! empty($caption) ? $caption : null,
                ]);
        }

        $orphanedImages = PostImage::where(function ($q) use ($post) {
            $q->where('post_id', $post->id)
                ->orWhere('temp_token', $this->tempToken);
        })
            ->whereNotIn('file_path', $activePaths)
            ->get();

        foreach ($orphanedImages as $orphan) {
            $orphan->delete();
        }
    }

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
