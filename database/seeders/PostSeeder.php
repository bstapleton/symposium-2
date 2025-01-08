<?php

namespace Database\Seeders;

use App\Enums\Role;
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
        $user2 = User::where('role', Role::USER)->orderBy('id', 'desc')->first();
        $date = now()->subDay()->addMinutes(rand(10, 300));

        // Create some posts without any revisions
        Post::factory()->count(3)->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(2),
        ])->map(function (Post $post) use ($user, $user2, $date) {
            // Create some replies to the post by someone else
            Reply::factory()->withParentPost($post->id)->count(3)->create([
                'user_id' => $user2->id,
                'created_at' => now()->subDay(),
            ])->map(function (Reply $reply) use ($post, $user, $date) {
                // Create a reply to each reply by the OP
                Reply::factory()->withParentReply($reply->id)->create([
                    'user_id' => $user->id,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            });
        });

        Post::first()->update(['slug' => 'no-revisions']);

        // Create one with a single revision
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Single revision',
        ]);

        PostRevision::factory()->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        Reply::factory()->withParentRevision($post->id)->count(3)->create([
            'user_id' => $user2->id,
            'created_at' => $date,
            'updated_at' => $date,
        ])->map(function (Reply $reply) use ($user, $date) {
            // Create a reply to each reply by the OP
            $replyDate = $date->addMinutes(rand(10, 300));
            Reply::factory()->withParentReply($reply->id)->create([
                'user_id' => $user->id,
                'created_at' => $replyDate,
                'updated_at' => $replyDate,
            ]);
        });

        // Create one with multiple revisions
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'title' => 'Multiple revisions',
        ]);

        Reply::factory(2)->create([
            'user_id' => $user2->id,
            'replyable_id' => $post->id,
            'replyable_type' => Post::class,
            'created_at' => $date,
            'updated_at' => $date,
        ]);

        PostRevision::factory()->count(2)->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
            'created_at' => $date->subMinutes(rand(10, 300)),
        ])->map(function (PostRevision $revision) use ($user2, $user, $date) {
            // Create some replies to the revision by someone else
            Reply::factory()->withParentRevision($revision->id)->count(3)->create([
                'user_id' => $user2->id,
                'created_at' => $date,
                'updated_at' => $date,
            ])->map(function (Reply $reply) use ($user, $date) {
                // Create a reply to each reply by the OP
                $replyDate = $date->addMinutes(rand(10, 300));
                Reply::factory()->withParentReply($reply->id)->create([
                    'user_id' => $user->id,
                    'created_at' => $replyDate,
                    'updated_at' => $replyDate,
                ]);
            });
        });

        // Fill out with some more older posts for pagination tests
        Post::factory()->count(100)
            ->make()
            ->map(function (Post $post) use ($user, $user2, $date) {
                $post->user_id = User::inRandomOrder()->first()->id;
                $post->created_at = now()->subDays(3)->subSeconds(rand(0, 604800));
                $post->save();
            });
    }
}
