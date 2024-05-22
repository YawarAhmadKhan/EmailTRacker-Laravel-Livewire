<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');

















// Route::get('/home/test', \App\Livewire\AdminDash::class)->name('dashboard')->middleware('auth');
// Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
//     Route::get('/home', \App\Livewire\AdminDash::class)->name('dashboard');
// });
