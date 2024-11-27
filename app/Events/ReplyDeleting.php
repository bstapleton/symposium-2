<?php

namespace App\Events;

use App\Models\Reply;
use Illuminate\Foundation\Events\Dispatchable;

class ReplyDeleting
{
    use Dispatchable;

    public function __construct(public Reply $reply)
    {
        // Nuke the replies
        $reply->replies->map(function ($reply) {
            $reply->delete();
        });
    }
}
