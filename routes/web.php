<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.index')->name('welcome');
Route::view('/article', 'article')->name('article');
