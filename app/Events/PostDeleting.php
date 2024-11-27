<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Foundation\Events\Dispatchable;

class PostDeleting
{
    use Dispatchable;

    public function __construct(public Post $post)
    {
        // Nuke the replies
        $post->replies->map(function ($reply) {
            $reply->delete();
        });

        // Nuke the revisions and their replies
        $post->revisions->map(function ($revision) {
            $revision->replies->map(function ($reply) {
                $reply->delete();
            });
            $revision->delete();
        });
    }
}
