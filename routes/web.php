<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\listArticleWorkspaceController;
use App\Http\Controllers\workspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('workspace')->group(function () {
        Route::get('/', [workspaceController::class, 'dashboard'])->name('workspace.dashboard');
        Route::get('/write', [workspaceController::class, 'write'])->name('workspace.write');
        Route::post('/write', [workspaceController::class, 'store'])->name('workspace.write.post');

        Route::get('/articles', [listArticleWorkspaceController::class, 'index'])->name('workspace.articles');
        Route::get('/article/{article:slug}/update', [workspaceController::class, 'update'])->name('workspace.article.update');
        Route::put('/article/{article:slug}/update', [workspaceController::class, 'edit'])->name('workspace.article.edit');

        Route::get('/monetization', [workspaceController::class, 'monetization'])->name('workspace.monetization');
        Route::get('/following', [workspaceController::class, 'following'])->name('workspace.following');
        Route::get('/followers', [workspaceController::class, 'followers'])->name('workspace.followers');
        Route::get('/profile', [workspaceController::class, 'profile'])->name('workspace.profile');
        Route::put('/profile', [workspaceController::class, 'updateProfile'])->name('workspace.profile.update');
        Route::post('/profile/photo', [workspaceController::class, 'updateProfilePhoto'])->name('workspace.profile.photo.update');
        Route::delete('/profile/photo', [workspaceController::class, 'deleteProfilePhoto'])->name('workspace.profile.photo.delete');
        Route::post('/profile/confirm-password', [workspaceController::class, 'confirmPassword'])->name('workspace.profile.confirm-password');
        Route::get('/profile/confirm-password', fn () => redirect()->route('workspace.profile')->with('confirm_password', true))->name('password.confirm');
        Route::middleware('password.confirm')->group(function () {
            Route::get('/profile/reset-password', [workspaceController::class, 'resetPassword'])->name('workspace.profile.reset-password');
            Route::put('/profile/reset-password', [workspaceController::class, 'updatePassword'])->name('workspace.profile.reset-password.update');
        });
        Route::get('/article/id/analytics', [workspaceController::class, 'analytics'])->name('workspace.article.analytics');
    });
});

Route::get('/article/{article:slug}', [IndexController::class, 'show'])->name('article.show');
Route::get('/', [IndexController::class, 'index'])->name('welcome');

Route::view('/terms', 'pages.terms')->name('terms');
Route::view('/privacy', 'pages.privacy')->name('privacy');

Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthController::class, 'signinView'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin.post');
    Route::get('/signup', [AuthController::class, 'signupView'])->name('signup');
    // Google Auth
    Route::get('/auth/google/', [AuthController::class, 'google'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'googleCallback']);

    Route::get('/forget-password', [AuthController::class, 'forgotPasswordView'])->name('forget-password');

});
