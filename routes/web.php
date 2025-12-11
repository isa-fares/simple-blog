<?php
// user home controller route
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProvisionServer;
use App\Http\Controllers\PhotoController;

// Route::get('/', [HomeController::class, 'index'])->name('index');
// Route::get('/user', [UserController::class, 'index'])->name('user.index');
// Route::redirect('/here', '/there', 301);
// Route::view('/there', 'there');
Route::get('/', function () {
    return "welcome to blade template";
});



Route::get('/login' , [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');

Route::get('/me', [AuthController::class, 'me'])->name('me');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');



Route::get('dashboard' , [AuthController::class , 'dashboard'])->name('dashboard')->middleware('auth.session');

// ==========================================
// 🌐 المقالات العامة (بدون تسجيل دخول)
// ==========================================
Route::get('/search', [PostController::class, 'search'])->name('posts.search');
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// ==========================================
// 🔐 يتطلب تسجيل دخول
// ==========================================
Route::middleware('auth.session')->group(function () {

    // إدارة المقالات
    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->name('posts.update');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('posts.destroy');

    // التعليقات
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('comments.store');

    Route::put('/comments/{comment}', [CommentController::class, 'update'])
        ->name('comments.update');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('comments.destroy');
});




// معالجة الطلب
Route::post('/provision-server', ProvisionServer::class);

Route::resources([
    'photos' => PhotoController::class,
]);
