<?php

use App\Http\Controllers\CarteRoseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaqueController;
use App\Http\Controllers\PostProduitController;
use App\Http\Controllers\PostTravailController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PropietaireController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculeController;
use App\Http\Middleware\AuthMiddleWare;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/auth/register', [UserController::class, 'register'])->name('auth.store');
Route::post('/auth/login', [UserController::class, 'login'])->name('auth.login');




    Route::get('/test-email', function () {
    Mail::raw('Ceci est un test depuis Laravel.', function ($message) {
        $message->to('t.alain1312@gmail.com')
                ->subject('Test SMTP Laravel');
    });

    return 'Email envoyé avec succès !';
});


Route::get('/test-php', function () {
    phpinfo();
});

Route::middleware(AuthMiddleWare::class)->group(function () {

    Route::post('/auth/logout', [UserController::class, 'logout'])
        ->name('auth.logout');

    Route::prefix('vehicules')
        ->name('vehicules.')
        ->controller(VehiculeController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::get('/show/{id}', 'show')->name('show');
            Route::post('/', 'store')->name('store');
        });

    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::resource('post-travails', PostTravailController::class);
    Route::resource('post-produits', PostProduitController::class);
    Route::resource('produits', ProduitController::class);
    Route::resource('proprietaires', PropietaireController::class);
    Route::resource('users', UserController::class);
    Route::resource('carte-roses', CarteRoseController::class);
    Route::resource('plaques', PlaqueController::class);

    Route::get('/carte/{id}', [CarteRoseController::class, 'imprimerCarte'])
        ->name('carte.imprimer');
});