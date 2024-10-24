<?php

namespace Database\Seeders;

use App\Models\PostHistory;
use Illuminate\Database\Seeder;

class PostHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a post's initial revision
        $postInitial = PostHistory::firstOrFail();

        // Change the title
        $postRevised = PostHistory::factory()->create([
            'title' => 'I am a revised title, my previous revision was: ' . $postInitial->id,
            'post_id' => $postInitial->post_id,
            'parent_id' => $postInitial->id,
        ]);

        // Now create a new revision with changed text
        PostHistory::factory()->create([
            'title' => $postRevised->title,
            'text' => 'I am some revised text, my previous revision was: ' . $postRevised->id,
            'post_id' => $postRevised->post_id,
            'parent_id' => $postRevised->id,
        ]);
    }
}
