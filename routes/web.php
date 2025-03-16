<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// トップページへのルート
Route::get('/', function () {
    return view('welcome');
});

// 認証関連ルート
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login')->with('message', 'ログアウトしました。');
})->name('logout');

// 新規登録画面ルート
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// 商品管理関連ルート（ミドルウェア適用）
Route::middleware(['web'])->group(function () {
    Route::resource('products', ProductController::class);
});

// メール確認関連ルート
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function () {
    Auth::user()->sendEmailVerificationNotification();
    return back()->with('message', '確認メールを再送信しました！');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

// ホームページルート（未使用時は削除可能）
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
