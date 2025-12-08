<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('123'),
            'role' => 'admin',
        ]);

        $writer = User::create([
            'name' => 'Writer',
            'email' => 'writer@example.com',
            'password' => bcrypt('password'),
            'role' => 'writer',
        ]);

        // إنشاء المقالات
        $posts = Post::factory(50)->create([
            'user_id' => fn (): mixed => fake()->randomElement([$admin->id, $writer->id]),
        ]);

        // إضافة تعليقات على كل مقال
        $posts->each(function ($post) use ($admin, $writer) {
            $users = [$admin, $writer];

            // 2-5 تعليقات رئيسية لكل مقال
            $parentComments = Comment::factory(rand(2, 5))->create([
                'post_id' => $post->id,
                'user_id' => fn () => fake()->randomElement($users)->id,
            ]);

            // ردود على بعض التعليقات الرئيسية
            $parentComments->each(function ($comment) use ($users) {
                // 50% احتمال يكون للتعليق ردود
                if (fake()->boolean(50)) {
                    // 1-3 ردود على التعليق
                    Comment::factory(rand(1, 3))->reply($comment)->create([
                        'user_id' => fn () => fake()->randomElement($users)->id,
                    ]);
                }
            });
        });
    }
}
