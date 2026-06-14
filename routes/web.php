<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\listArticleWorkspaceController;
use App\Http\Controllers\workspaceController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function(){

    Route::post('logout',[AuthController::class, 'logout'])->name('logout');

    Route::prefix('workspace')->group(function(){
        Route::get('/', [workspaceController::class, 'dashboard'])->name('workspace.dashboard');
        Route::get('/write', [workspaceController::class, 'write'])->name('workspace.write');
        Route::post('/write', [workspaceController::class, 'store'])->name('workspace.write.post');
            
        Route::get('/articles', [listArticleWorkspaceController::class, 'index'])->name('workspace.articles');
        Route::put('/article/{article:slug}/status', [listArticleWorkspaceController::class,'status'])->name('workspace.article.status');
        Route::put('/article/{article:slug}/type', [listArticleWorkspaceController::class, 'type'])->name('workspace.article.type');
        Route::get('/article/{article:slug}/update', [workspaceController::class, 'update'])->name('workspace.article.update');
        Route::put('/article/{article:slug}/update', [workspaceController::class, 'edit'])->name('workspace.article.edit');
        Route::delete('/article/{article:slug}/delete', [workspaceController::class, 'delete'])->name('workspace.article.delete');
        
        Route::get('/monetization', [workspaceController::class, 'monetization'])->name('workspace.monetization');
        Route::get('/following', [workspaceController::class, 'following'])->name('workspace.following');
        Route::get('/followers', [workspaceController::class, 'followers'])->name('workspace.followers');
        Route::get('/profile', [workspaceController::class, 'profile'])->name('workspace.profile');
        Route::get('/article/id/analytics', [workspaceController::class, 'analytics'])->name('workspace.article.analytics');
    });
});

    

Route::get('/article/{article:slug}', [IndexController::class, 'show'])->name('article.show');
Route::get('/', [IndexController::class, 'index'])->name('welcome');

Route::middleware('guest')->group(function(){
    Route::get('/signin', [AuthController::class, 'signinView'])->name('signin');
    Route::post('/signin', [AuthController::class, 'signin'])->name('signin.post');
    Route::get('/signup', [AuthController::class, 'signupView'])->name('signup');
    Route::post('/signup', [AuthController::class, 'signup'])->name('signup.post');
});
