<?php

namespace Tests\Feature;

use Tests\TestCase;

class SimpleTest extends TestCase
{
    /**
     * 🎯 الهدف: نتأكد إن الصفحة الرئيسية تفتح
     *
     * الخطوات:
     * 1. روح للصفحة الرئيسية '/'
     * 2. تأكد إنها ترجع status code 200 (يعني نجح)
     */
    public function test_homepage_works()
    {
        // Act: نفذ الإجراء - اطلب الصفحة الرئيسية
        $response = $this->get('/');

        // Assert: تحقق من النتيجة - لازم يكون 200
        $response->assertStatus(200);
    }

    /**
     * 🎯 الهدف: نتأكد إن صفحة الدخول فيها كلمة "تسجيل"
     */
    public function test_login_page_has_text()
    {
        $response = $this->get('/login');

        // تأكد إن الصفحة تحتوي النص "تسجيل الدخول"
        $response->assertSee('تسجيل الدخول');
    }

    /**
     * 🎯 الهدف: نتأكد إن صفحة غير موجودة ترجع 404
     */
    public function test_nonexistent_page_returns_404()
    {
        $response = $this->get('/صفحة-ما-موجودة-أبداً');

        $response->assertStatus(404);
    }
}
