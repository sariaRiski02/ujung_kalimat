<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('welcome');
Route::view('/article', 'pages.pageArticle.article')->name('article');
Route::view('/workspace', 'pages.pageWorkspace.dashboard')->name('workspace.dashboard');
Route::view('/workspace/write', 'pages.pageWorkspace.write')->name('workspace.write');
Route::view('/workspace/articles', 'pages.pageWorkspace.listArticle')->name('workspace.articles');
Route::view('/workspace/monetization', 'pages.pageWorkspace.monetization')->name('workspace.monetization');
Route::view('/workspace/following', 'pages.pageWorkspace.following')->name('workspace.following');
Route::view('/workspace/followers', 'pages.pageWorkspace.followers')->name('workspace.followers');
Route::view('/workspace/profile', 'pages.pageWorkspace.profile')->name('workspace.profile');
Route::view('/workspace/article/id/analytics', 'pages.pageWorkspace.analyticDetailArticle')->name('workspace.article.analytics');


Route::view('/signin', 'pages.auth.signin')->name('signin');
Route::view('/signup', 'pages.auth.signup')->name('signup');