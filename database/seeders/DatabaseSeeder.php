<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            Post::factory(5)
                ->has(Comment::factory(
                    5,
                    fn (): array => [
                        'user_id' => $users->where('id', '!=', $user->id)->random()->id,
                    ]
                ))
                ->create([
                    'user_id' => $user->id,
                ]);
        }
    }
}
