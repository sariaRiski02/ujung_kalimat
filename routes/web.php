<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\listArticleWorkspaceController;
use App\Http\Controllers\workspaceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('welcome');
Route::get('/article/{article:slug}', [IndexController::class, 'show'])->name('article.show');
Route::view('/article', 'pages.pageArticle.article')->name('article');


Route::group(['prefix' => 'workspace'], function(){
    Route::get('/', [workspaceController::class, 'dashboard'])->name('workspace.dashboard');
    Route::get('/write', [workspaceController::class, 'write'])->name('workspace.write');
    Route::post('/write', [workspaceController::class, 'store'])->name('workspace.write.post');
    
    
    Route::get('/articles', [listArticleWorkspaceController::class, 'index'])->name('workspace.articles');
    Route::put('/article/{article:slug}/status', [listArticleWorkspaceController::class,'status'])->name('workspace.article.status');
    Route::put('/article/{article:slug}/type', [listArticleWorkspaceController::class, 'type'])->name('workspace.article.tipe');
    
    
    Route::get('/monetization', [workspaceController::class, 'monetization'])->name('workspace.monetization');
    Route::get('/following', [workspaceController::class, 'following'])->name('workspace.following');
    Route::get('/followers', [workspaceController::class, 'followers'])->name('workspace.followers');
    Route::get('/profile', [workspaceController::class, 'profile'])->name('workspace.profile');
    Route::get('/article/id/analytics', [workspaceController::class, 'analytics'])->name('workspace.article.analytics');

});



Route::get('/signin', [IndexController::class, 'signin'])->name('signin');
Route::get('/signup', [IndexController::class, 'signup'])->name('signup');