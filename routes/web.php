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