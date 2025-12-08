<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * هل المستخدم مصرح له بهذا الطلب؟
     */
    public function authorize(): bool
    {
        // نسمح لأي مستخدم مسجل دخول
        return $this->session()->has('user_id');
    }

    /**
     * قواعد التحقق من المدخلات
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'content' => ['required', 'string', 'min:10'],
        ];
    }

    /**
     * رسائل الخطأ المخصصة
     */
    public function messages(): array
    {
        return [
            'title.required' => 'العنوان مطلوب',
            'title.min' => 'العنوان يجب أن يكون 3 أحرف على الأقل',
            'title.max' => 'العنوان يجب ألا يتجاوز 255 حرف',
            'content.required' => 'المحتوى مطلوب',
            'content.min' => 'المحتوى يجب أن يكون 10 أحرف على الأقل',
        ];
    }
}
