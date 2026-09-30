<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PostImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostMediaController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'file', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'temp_token' => ['nullable', 'string', 'max:64'],
            'post_id' => ['nullable', 'integer', 'exists:posts,id'],
        ]);

        $file = $request->file('image');
        $path = $file->store('posts/inline', 'public');

        $postImage = PostImage::create([
            'post_id' => $validated['post_id'] ?? null,
            'user_id' => $request->user()->id,
            'disk' => 'public',
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType() ?: 'image/jpeg',
            'temp_token' => $validated['temp_token'] ?? null,
        ]);

        return response()->json([
            'url' => Storage::disk('public')->url($path),
            'id' => $postImage->id,
        ]);
    }
}
