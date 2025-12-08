<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    // RefreshDatabase - ينظف قاعدة البيانات قبل كل اختبار
    use RefreshDatabase;

    // ================================================
    // اختبارات العرض العام (بدون تسجيل دخول)
    // ================================================

    public function test_guests_can_view_posts_index()
    {
        // 1️⃣ Arrange: تجهيز البيانات
        $user = User::factory()->create();
        Post::factory(3)->create([
            'user_id' => $user->id,
            'is_published' => true,
        ]);

        // 2️⃣ Act: تنفيذ الإجراء
        $response = $this->get('/posts');

        // 3️⃣ Assert: التحقق من النتيجة
        $response->assertStatus(200);
    }

    public function test_guests_can_view_published_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_published' => true,
        ]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(200);
        $response->assertSee($post->title);
    }

    public function test_guests_cannot_view_draft_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'is_published' => false, // مسودة
        ]);

        $response = $this->get("/posts/{$post->id}");

        $response->assertStatus(403); // Forbidden
    }

    // ================================================
    // اختبارات الإنشاء (يتطلب تسجيل دخول)
    // ================================================

    public function test_guests_cannot_access_create_page()
    {
        $response = $this->get('/posts/create');

        $response->assertRedirect('/login');
    }

    public function test_writer_can_create_post()
    {
        // تجهيز كاتب
        $writer = User::factory()->create(['role' => 'writer']);

        // تسجيل الدخول عبر Session
        $response = $this->withSession(['user_id' => $writer->id])
            ->post('/posts', [
                'title' => 'مقال اختباري',
                'content' => 'هذا محتوى المقال الاختباري للتجربة',
                'is_published' => true,
            ]);

        // التحقق من الحفظ
        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('posts', [
            'title' => 'مقال اختباري',
            'user_id' => $writer->id,
        ]);
    }

    public function test_user_cannot_create_post()
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->withSession(['user_id' => $user->id])
            ->post('/posts', [
                'title' => 'مقال',
                'content' => 'محتوى المقال هنا للاختبار',
            ]);

        $response->assertStatus(403);
    }

    // ================================================
    // اختبارات التعديل
    // ================================================

    public function test_writer_can_edit_own_post()
    {
        $writer = User::factory()->create(['role' => 'writer']);
        $post = Post::factory()->create(['user_id' => $writer->id]);

        $response = $this->withSession(['user_id' => $writer->id])
            ->put("/posts/{$post->id}", [
                'title' => 'عنوان معدل',
                'content' => 'محتوى معدل للاختبار هنا',
                'is_published' => true,
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'عنوان معدل',
        ]);
    }

    public function test_writer_cannot_edit_others_post()
    {
        $writer1 = User::factory()->create(['role' => 'writer']);
        $writer2 = User::factory()->create(['role' => 'writer']);
        $post = Post::factory()->create(['user_id' => $writer1->id]);

        $response = $this->withSession(['user_id' => $writer2->id])
            ->put("/posts/{$post->id}", [
                'title' => 'محاولة تعديل',
                'content' => 'محتوى',
            ]);

        $response->assertStatus(403);
    }

    public function test_admin_can_edit_any_post()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $writer = User::factory()->create(['role' => 'writer']);
        $post = Post::factory()->create(['user_id' => $writer->id]);

        $response = $this->withSession(['user_id' => $admin->id])
            ->put("/posts/{$post->id}", [
                'title' => 'عدله الأدمن',
                'content' => 'المحتوى المعدل من الأدمن',
                'is_published' => true,
            ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'عدله الأدمن',
        ]);
    }

    // ================================================
    // اختبارات الحذف
    // ================================================

    public function test_writer_can_delete_own_post()
    {
        $writer = User::factory()->create(['role' => 'writer']);
        $post = Post::factory()->create(['user_id' => $writer->id]);

        $response = $this->withSession(['user_id' => $writer->id])
            ->delete("/posts/{$post->id}");

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_writer_cannot_delete_others_post()
    {
        $writer1 = User::factory()->create(['role' => 'writer']);
        $writer2 = User::factory()->create(['role' => 'writer']);
        $post = Post::factory()->create(['user_id' => $writer1->id]);

        $response = $this->withSession(['user_id' => $writer2->id])
            ->delete("/posts/{$post->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    // ================================================
    // اختبارات Validation
    // ================================================

    public function test_post_requires_title_and_content()
    {
        $writer = User::factory()->create(['role' => 'writer']);

        $response = $this->withSession(['user_id' => $writer->id])
            ->post('/posts', [
                'title' => '', // فارغ
                'content' => '', // فارغ
            ]);

        $response->assertSessionHasErrors(['title', 'content']);
    }

    public function test_post_title_must_be_at_least_3_characters()
    {
        $writer = User::factory()->create(['role' => 'writer']);

        $response = $this->withSession(['user_id' => $writer->id])
            ->post('/posts', [
                'title' => 'ab', // أقل من 3
                'content' => 'محتوى كافي للاختبار هنا',
            ]);

        $response->assertSessionHasErrors('title');
    }
}
