<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', function () {
        return view('auth.register');
    })->name('register');

    Route::get('login', function () {
        return view('auth.login');
    })->name('login');
});

Route::post('logout', function () {
    auth()->logout();
    return redirect('/');
})->middleware('auth')->name('logout');
