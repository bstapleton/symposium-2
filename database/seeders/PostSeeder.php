<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostRevision;
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

        // Create some posts without any revisions
        Post::factory()->count(3)->create([
            'user_id' => $user->id,
        ])->map(function (Post $post) use ($user, $user2) {
            // Create some replies to the post by someone else
            Reply::factory()->count(3)->create([
                'user_id' => $user2->id,
                'replyable_id' => $post->id,
                'replyable_type' => Post::class,
            ])->map(function (Reply $reply) use ($post, $user) {
                // Create a reply to each reply by the OP
                $replyReply = Reply::factory()->create([
                    'user_id' => $user->id,
                    'parent_id' => $reply->id,
                    'replyable_id' => $post->id,
                    'replyable_type' => PostRevision::class,
                ]);
            });
        });

        Post::factory()->count(3)->create([
            'user_id' => $user->id,
        ])->map(function (Post $post) use ($user, $user2) {
            // Create the revision data
            $postRevision = PostRevision::factory()->create([
                'post_id' => $post->id,
                'user_id' => $user->id,
            ]);

            // Create some replies to the post by someone else
            Reply::factory()->count(3)->create([
                'user_id' => $user2->id,
                'replyable_id' => $post->id,
                'replyable_type' => Post::class,
            ])->map(function (Reply $reply) use ($postRevision, $user) {
                // Create a reply to each reply by the OP
                $replyReply = Reply::factory()->create([
                    'user_id' => $user->id,
                    'parent_id' => $reply->id,
                    'replyable_id' => $postRevision->id,
                    'replyable_type' => PostRevision::class,
                ]);
            });
        });
    }
}
