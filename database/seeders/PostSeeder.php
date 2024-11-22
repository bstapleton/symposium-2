<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostHistory;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrFail();
        $user2 = User::orderBy('id', 'desc')->first();

        Post::factory()->count(3)->create([
            'user_id' => $user->id,
        ])->map(function (Post $post) use ($user, $user2) {
            // Create the revision data
            $postRevision = PostHistory::factory()->create([
                'post_id' => $post->id,
            ]);

            // Create some replies to the post by someone else
            Reply::factory()->count(3)->create([
                'user_id' => $user2->id,
                'post_history_id' => $postRevision->id,
            ])->map(function (Reply $reply) use ($postRevision, $user) {
                // Create a reply to each reply by the OP
                $replyReply = Reply::factory()->create([
                    'user_id' => $user->id,
                    'post_history_id' => $postRevision->id,
                ]);
            });
        });
    }
}
