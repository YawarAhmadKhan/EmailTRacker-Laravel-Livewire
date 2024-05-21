<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', \App\Livewire\AdminDash::class)->name('dashboard')->middleware('auth');
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    // Route::get('/home', \App\Livewire\AdminDash::class)->name('dashboard');
});
