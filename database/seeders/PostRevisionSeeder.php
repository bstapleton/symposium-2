<?php

namespace Database\Seeders;

use App\Models\PostRevision;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostRevisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrFail();

        // Get a post's initial revision
        $postInitial = PostRevision::firstOrFail();

        // Change the title
        $postRevised = PostRevision::factory()->create([
            'title' => 'I am a revised title, my previous revision was: ' . $postInitial->id,
            'post_id' => $postInitial->post_id,
            'user_id' => $user->id,
        ]);

        // Now create a new revision with changed text
        PostRevision::factory()->create([
            'title' => $postRevised->title,
            'text' => 'I am some revised text, my previous revision was: ' . $postRevised->id,
            'post_id' => $postRevised->post_id,
            'user_id' => $user->id,
        ]);
    }
}
