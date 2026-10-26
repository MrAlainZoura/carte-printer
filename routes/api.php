<?php

use App\Http\Controllers\Api\ApprovisionnementController;
use App\Http\Controllers\Api\CarteRoseController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\PlaqueController;
use App\Http\Controllers\Api\PostProduitController;
use App\Http\Controllers\Api\PostSelectionController;
use App\Http\Controllers\Api\PostTravailController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\PropietaireController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VehiculeController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\JwtMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);
Route::get('register/options', [AuthController::class, 'registrationOptions'])->middleware('throttle:30,1');
Route::post('register', [AuthController::class, 'register'])->middleware('throttle:5,1');

Route::middleware(JwtMiddleware::class)->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('postes', [PostSelectionController::class, 'show']);
    Route::post('postes/select', [PostSelectionController::class, 'store']);
    Route::post('postes/global', [PostSelectionController::class, 'globalDashboard'])
        ->middleware(['role:admin', 'permission:dashboard.global']);
    Route::post('postes/{id}/open', [PostSelectionController::class, 'openPost'])
        ->middleware('role:admin');

    Route::get('users/profile', [UserController::class, 'profile']);
    Route::put('users/password', [UserController::class, 'updatePassword']);

    Route::middleware('api.post.selected')->group(function () {
        Route::get('dashboard', DashboardController::class);

        Route::prefix('vehicules')->controller(VehiculeController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:vehicule.view');
            Route::get('/create', 'create')->middleware('permission:vehicule.create');
            Route::post('/', 'store')->middleware('permission:vehicule.create');
            Route::get('/{id}/proprietaire', 'transferOwnerForm')->middleware('permission:vehicule.transfer');
            Route::post('/{id}/proprietaire', 'transferOwner')->middleware('permission:vehicule.transfer');
            Route::get('/{id}/historique-proprietaires', 'ownerHistory')->middleware('permission:vehicule.view');
            Route::get('/{id}/plaque', 'changePlaqueForm')->middleware('permission:vehicule.transfer|plaque.update');
            Route::post('/{id}/plaque', 'changePlaque')->middleware('permission:vehicule.transfer|plaque.update');
            Route::get('/{id}/edit', 'edit')->middleware('permission:vehicule.update');
            Route::put('/{id}', 'update')->middleware('permission:vehicule.update');
            Route::delete('/{id}', 'destroy')->middleware('permission:vehicule.delete');
            Route::get('/{id}', 'show')->middleware('permission:vehicule.view');
        });

        Route::get('vehicules/{vehicule}/plaque/{plaque}/edit', [PlaqueController::class, 'edit'])
            ->middleware('permission:vehicule.update|plaque.update');
        Route::put('vehicules/{vehicule}/plaque/{plaque}', [PlaqueController::class, 'update'])
            ->middleware('permission:vehicule.update|plaque.update');
        Route::get('plaques/{plaque}', [PlaqueController::class, 'show'])
            ->middleware('permission:plaque.view');

        Route::get('vehicules/{vehicule}/carte-rose/{carteRose}/edit', [CarteRoseController::class, 'edit'])
            ->middleware('permission:vehicule.update|carte_rose.update');
        Route::put('vehicules/{vehicule}/carte-rose/{carteRose}', [CarteRoseController::class, 'update'])
            ->middleware('permission:vehicule.update|carte_rose.update');
        Route::get('carte/{id}', [CarteRoseController::class, 'print'])
            ->middleware('permission:carte_rose.print');

        Route::prefix('post-travails')->controller(PostTravailController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:post_travail.view');
            Route::get('/create', 'create')->middleware('permission:post_travail.create');
            Route::post('/', 'store')->middleware('permission:post_travail.create');
            Route::get('/{id}/edit', 'edit')->middleware('permission:post_travail.manage');
            Route::put('/{id}', 'update')->middleware('permission:post_travail.manage');
            Route::patch('/{id}', 'update')->middleware('permission:post_travail.manage');
            Route::delete('/{id}', 'destroy')->middleware('permission:post_travail.manage');
            Route::get('/{id}', 'show')->middleware('permission:post_travail.view');
        });

        Route::prefix('post-produits')->controller(PostProduitController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:post_produit.view');
            Route::get('/create', 'create')->middleware('permission:post_produit.create');
            Route::post('/', 'store')->middleware('permission:post_produit.create');
            Route::get('/{id}/edit', 'edit')->middleware('permission:post_produit.manage');
            Route::put('/{id}', 'update')->middleware('permission:post_produit.manage');
            Route::patch('/{id}', 'update')->middleware('permission:post_produit.manage');
            Route::delete('/{id}', 'destroy')->middleware('permission:post_produit.manage');
            Route::get('/{id}', 'show')->middleware('permission:post_produit.view');
        });

        Route::prefix('approvisionnements')->controller(ApprovisionnementController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:approvisionnement.view');
            Route::get('/create', 'create')->middleware('permission:approvisionnement.create');
            Route::post('/', 'store')->middleware('permission:approvisionnement.create');
            Route::patch('/{id}/confirm', 'confirm')->middleware('permission:approvisionnement.confirm');
            Route::get('/{id}/edit', 'edit')->middleware('permission:approvisionnement.manage');
            Route::put('/{id}', 'update')->middleware('permission:approvisionnement.manage');
            Route::delete('/{id}', 'destroy')->middleware('permission:approvisionnement.manage');
            Route::get('/{id}', 'show')->middleware('permission:approvisionnement.view');
        });

        Route::prefix('produits')->controller(ProduitController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:produit.view');
            Route::get('/create', 'create')->middleware('permission:produit.create');
            Route::post('/', 'store')->middleware('permission:produit.create');
            Route::get('/{id}/edit', 'edit')->middleware('permission:produit.manage');
            Route::put('/{id}', 'update')->middleware('permission:produit.manage');
            Route::patch('/{id}', 'update')->middleware('permission:produit.manage');
            Route::delete('/{id}', 'destroy')->middleware('permission:produit.manage');
            Route::get('/{id}', 'show')->middleware('permission:produit.view');
        });

        Route::prefix('proprietaires')->controller(PropietaireController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:proprietaire.view');
            Route::get('/create', 'create')->middleware('permission:proprietaire.create');
            Route::post('/', 'store')->middleware('permission:proprietaire.create');
            Route::get('/{id}/edit', 'edit')->middleware('permission:proprietaire.manage');
            Route::put('/{id}', 'update')->middleware('permission:proprietaire.manage');
            Route::patch('/{id}', 'update')->middleware('permission:proprietaire.manage');
            Route::delete('/{id}', 'destroy')->middleware('permission:proprietaire.manage');
            Route::get('/{id}', 'show')->middleware('permission:proprietaire.view');
        });

        Route::prefix('users')->controller(UserController::class)->group(function () {
            Route::get('/', 'index')->middleware(['permission:user.view', 'role:admin']);
            Route::get('/create', 'create')->middleware('permission:user.create');
            Route::post('/', 'store')->middleware('permission:user.create');
            Route::post('/{id}/reset-password', 'resetPassword')->middleware(['permission:user.manage', 'role:admin']);
            Route::get('/{id}/edit', 'edit')->middleware('permission:user.manage');
            Route::put('/{id}', 'update')->middleware('permission:user.manage');
            Route::patch('/{id}', 'update')->middleware('permission:user.manage');
            Route::delete('/{id}', 'destroy')->middleware('permission:user.manage');
            Route::get('/{id}', 'show')->middleware('permission:user.view');
        });

        Route::prefix('roles')->middleware('permission:role.manage')->controller(RoleController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/create', 'create');
            Route::post('/', 'store');
            Route::put('/{role}/permissions', 'syncPermissions');
            Route::get('/{role}/edit', 'edit');
            Route::put('/{role}', 'update');
            Route::delete('/{role}', 'destroy');
            Route::get('/{role}', 'show');
        });
    });

    Route::post('initialisation/poste', [PostTravailController::class, 'storeInitialSetup'])
        ->middleware('role:admin');
    Route::get('initialisation/poste', [PostTravailController::class, 'initialSetup'])
        ->middleware('role:admin');
});
