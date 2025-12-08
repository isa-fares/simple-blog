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
        //  مدير - يقدر على كل شيء
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('123'),
            'role' => 'admin',
        ]);

        // ✍️ كاتب - يقدر ينشئ ويعدل ويحذف مقالاته فقط
        $writer = User::create([
            'name' => 'Writer',
            'email' => 'writer@example.com',
            'password' => bcrypt('123'),
            'role' => 'writer',
        ]);

        // 👤 مستخدم عادي - يقدر يشوف فقط
        $user = User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('123'),
            'role' => 'user',
        ]);

        // إنشاء المقالات
        $posts = Post::factory(50)->create([
            'user_id' => fn (): mixed => fake()->randomElement([$admin->id, $writer->id]),
        ]);

        // إضافة تعليقات على كل مقال
        $allUsers = [$admin, $writer, $user];
        $posts->each(function ($post) use ($allUsers) {
            // 2-5 تعليقات رئيسية لكل مقال
            $parentComments = Comment::factory(rand(2, 5))->create([
                'post_id' => $post->id,
                'user_id' => fn () => fake()->randomElement($allUsers)->id,
            ]);

            // ردود على بعض التعليقات الرئيسية
            $parentComments->each(function ($comment) use ($allUsers) {
                if (fake()->boolean(50)) {
                    Comment::factory(rand(1, 3))->reply($comment)->create([
                        'user_id' => fn () => fake()->randomElement($allUsers)->id,
                    ]);
                }
            });
        });
    }
}
