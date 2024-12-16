<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->revisionSystem()->create([
            'name' => 'Normal User',
            'email' => 'user@example.com',
        ]);

        User::factory()->moderator()->create([
            'name' => 'Moderator User',
            'email' => 'moderator@example.com',
        ]);

        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);

        User::factory()->unverified()->create([
            'name' => 'Unverified User',
            'email' => 'unverified@example.com',
        ]);

        // Create another user (for replies and such)
        User::factory()->create();
    }
}
