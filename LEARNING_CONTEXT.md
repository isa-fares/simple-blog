# 📚 Laravel Learning Journey - Context File

## 🎯 المشروع: مدونة بسيطة متعددة الأدوار

---

## ⚠️ تعليمات مهمة للـ AI Assistant

**الهدف الرئيسي: التعلم العميق وليس الإنجاز السريع!**

عند شرح أي مفهوم في Laravel، اتبع هذا الإطار:

### 1️⃣ **شو هو هذا المفهوم؟**
- تعريف بسيط وواضح
- ليش اخترعوه أو ليش موجود؟

### 2️⃣ **ليش مهم؟**
- شو المشكلة اللي بيحلها؟
- شو اللي كان يصير قبله؟

### 3️⃣ **كيف بيشتغل؟**
- آلية العمل بشكل مبسط
- شرح كل خطوة بالتفصيل

### 4️⃣ **متى وأين أستخدمه؟**
- في أي حالات أو مشاريع أحتاجه؟
- كيف أعرف إنه الحل المناسب؟
- أمثلة واقعية

### 5️⃣ **أخطاء شائعة لازم أتجنبها**
- المشاكل اللي ممكن تواجهني
- Best Practices

---

**القواعد الأساسية:**
- ✅ خطوات بسيطة ومفصلة
- ✅ شرح **لماذا** قبل **كيف**
- ✅ أمثلة عملية بسيطة
- ❌ لا تكتب كود كثير بدون شرح
- ❌ لا تفترض معرفة مسبقة

**نحن هنا لصقل الفهم، وليس لتطوير موقع!**

---

## ✅ ما تم تعلمه وتطبيقه

### 1️⃣ Database & Models
- [x] Migrations (users, posts, comments)
- [x] Models with Relationships (hasMany, belongsTo)
- [x] Self-referencing (Comments → Replies)
- [x] Factories & Seeders
- [x] Eager Loading (N+1 Problem)

### 2️⃣ Authentication
- [x] Custom Session Auth (بدون Breeze/Sanctum)
- [x] Login / Logout
- [x] AuthSessionMiddleware
- [x] Rate Limiting (حماية من brute force)

### 3️⃣ Controllers & CRUD
- [x] PostController (full CRUD)
- [x] CommentController (store, update, destroy)
- [x] AuthController (login, logout, dashboard)

### 4️⃣ Views & Blade
- [x] Blade Templates
- [x] Blade Directives (@if, @foreach, @forelse)
- [x] Blade Partials
- [x] Custom CSS + RTL Design

### 5️⃣ Validation
- [x] Form Request (StorePostRequest)
- [x] Custom Arabic Messages
- [x] Validation Rules

### 6️⃣ Events & Listeners
- [x] PostCreated Event
- [x] LogPostCreated Listener
- [x] Event Registration

### 7️⃣ Authorization (Policies)
- [x] PostPolicy
- [x] Gates Registration
- [x] AuthorizationService
- [x] Roles (admin, writer, user)

### 8️⃣ Features
- [x] Search (LIKE query)
- [x] Nested Comments
- [x] Pagination
- [x] Logs Viewer (للأدمن)

### 9️⃣ Performance
- [x] Eager Loading
- [x] Caching (تجربة)

---

## ❌ ما لم يتم تعلمه بعد

### 🔴 أساسيات مهمة (الأولوية القصوى)
| # | الموضوع | الوصف |
|---|---------|-------|
| 1 | **Testing** | Unit Tests, Feature Tests, PHPUnit/Pest |
| 2 | **API Development** | REST API, JSON Responses, Sanctum Tokens |
| 3 | **File Upload** | رفع الصور، Storage، Validation |
| 4 | **Queues & Jobs** | Background Tasks، Email Queue |
| 5 | **Notifications** | Email، Database، Real-time |

### 🟡 متوسطة الأهمية
| # | الموضوع | الوصف |
|---|---------|-------|
| 6 | Blade Components | Reusable Components |
| 7 | Localization (i18n) | Multi-language Support |
| 8 | Soft Deletes | حذف ناعم + استعادة |
| 9 | Observers | Model Event Observers |
| 10 | Accessors/Mutators | Data Transformation |

### 🟢 متقدمة
| # | الموضوع | الوصف |
|---|---------|-------|
| 11 | Service Container | Dependency Injection |
| 12 | Service Providers | Application Bootstrap |
| 13 | Broadcasting | Real-time (Pusher/Soketi) |
| 14 | Task Scheduling | Cron Jobs |
| 15 | Package Development | إنشاء Packages |

---

## 📁 هيكل المشروع

```
app/
├── Events/PostCreated.php
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── PostController.php
│   │   └── CommentController.php
│   ├── Middleware/AuthSessionMiddleware.php
│   └── Requests/StorePostRequest.php
├── Listeners/LogPostCreated.php
├── Models/
│   ├── User.php
│   ├── Post.php
│   └── Comment.php
├── Policies/PostPolicy.php
├── Providers/AppServiceProvider.php
└── Services/AuthorizationService.php

database/
├── factories/
├── migrations/
└── seeders/

resources/views/
├── auth/login.blade.php
├── dashboard.blade.php
└── posts/
    ├── index.blade.php
    ├── show.blade.php
    ├── create.blade.php
    ├── edit.blade.php
    ├── search.blade.php
    └── partials/comment.blade.php
```

---

## 👥 المستخدمين للاختبار

| الدور | الإيميل | كلمة المرور | الصلاحيات |
|-------|---------|-------------|-----------|
| Admin | admin@example.com | 123 | كل شيء |
| Writer | writer@example.com | password | CRUD لمقالاته |
| User | user@example.com | password | عرض فقط |

---

## 🔗 الـ Routes الرئيسية

### 🌐 عام (بدون تسجيل)
```
GET  /posts          → قائمة المقالات
GET  /posts/{id}     → عرض مقال
GET  /search         → البحث
GET  /login          → صفحة الدخول
```

### 🔐 يتطلب تسجيل دخول
```
GET  /dashboard      → لوحة التحكم
GET  /posts/create   → إنشاء مقال
POST /posts          → حفظ مقال
GET  /posts/{id}/edit → تعديل مقال
PUT  /posts/{id}     → تحديث مقال
DELETE /posts/{id}   → حذف مقال
POST /posts/{id}/comments → إضافة تعليق
```

---

## 📝 ملاحظات مهمة

1. **Custom Auth**: نستخدم Session بدون Laravel Auth Guard
2. **Policy Integration**: عبر AuthorizationService
3. **Database**: SQLite
4. **Session Driver**: Database
5. **Cache Driver**: File

---

## 🎯 الخطوة التالية المقترحة

**Testing** أو **API Development** - الأكثر طلباً في سوق العمل
