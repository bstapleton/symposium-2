<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Str;

class PostCreating
{
    use Dispatchable;

    public function __construct(public Post $post)
    {
        $slug = Str::slug($post->title);
        $existingPost = Post::where('slug', $slug)->first();

        if ($existingPost) {
            // If it's a duplicate slug, append the post's ID
            $slug .= '-' . $post->id;
        }

        $post->slug = $slug;
    }
}
