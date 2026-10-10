<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehiculeController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('dashboard.index');})->name('dashboard');

Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/auth/register', [AuthController::class, 'register'])->name('auth.store');
Route::post('/auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');


Route::prefix('vehicules')
    ->name('vehicules.')
    ->controller(VehiculeController::class)
    ->group(function () {

        Route::get('/', 'index')->name('index');

        Route::get('/create', 'create')->name('create');

        Route::post('/', 'store')->name('store');

    });

    Route::get('/test-email', function () {
    Mail::raw('Ceci est un test depuis Laravel.', function ($message) {
        $message->to('t.alain1312@gmail.com')
                ->subject('Test SMTP Laravel');
    });

    return 'Email envoyé avec succès !';
});