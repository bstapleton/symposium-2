<?php

namespace Database\Seeders;

use App\Models\ReplyHistory;
use Illuminate\Database\Seeder;

class ReplyHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a reply's initial revision
        $replyInitial = ReplyHistory::firstOrfail();

        // Create a new revision with updated content
        ReplyHistory::factory()->create([
            'text' => 'I am a revised reply, my previous revision was: ' . $replyInitial->id,
            'reply_id' => $replyInitial->reply_id,
            'parent_id' => $replyInitial->id,
        ]);
    }
}
