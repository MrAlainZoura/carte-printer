<?php

use App\Http\Controllers\CarteRoseController;
use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PlaqueController;
use App\Http\Controllers\PostProduitController;
use App\Http\Controllers\PostSelectionController;
use App\Http\Controllers\PostTravailController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\PropietaireController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehiculeController;
use App\Http\Middleware\AuthMiddleWare;
use App\Models\PostTravail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register', [
        'posts' => PostTravail::query()->where('actif', true)->orderBy('libele')->get(),
        'roles' => User::assignableRoleLabels(),
    ]);
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

    Route::get('/choix-poste', [PostSelectionController::class, 'show'])
        ->name('postes.select');
    Route::post('/choix-poste', [PostSelectionController::class, 'store'])
        ->name('postes.select.store');
    Route::get('/mot-de-passe', [UserController::class, 'editPassword'])
        ->name('password.change');
    Route::put('/mot-de-passe', [UserController::class, 'updatePassword'])
        ->name('password.change.update');
    Route::get('/mon-profil', [UserController::class, 'profile'])
        ->name('users.profile');
    Route::get('/dashboard/global', [PostSelectionController::class, 'globalDashboard'])
        ->middleware(['role:admin', 'permission:dashboard.global'])
        ->name('dashboard.global');
    Route::get('/dashboard/poste/{id}', [PostSelectionController::class, 'openPost'])
        ->middleware('role:admin')
        ->name('dashboard.post.open');

    Route::middleware('post.selected')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        // Véhicules
        Route::prefix('vehicules')->name('vehicules.')->controller(VehiculeController::class)->group(function () {
            Route::get('/', 'index')->middleware('permission:vehicule.view')->name('index');
            Route::get('/create', 'create')->middleware('permission:vehicule.create')->name('create');
            Route::post('/', 'store')->middleware('permission:vehicule.create')->name('store');
            Route::get('/show/{id}', 'show')->middleware('permission:vehicule.view')->name('show');
            Route::get('/{id}/edit', 'edit')->middleware('permission:vehicule.update')->name('edit');
            Route::put('/{id}', 'update')->middleware('permission:vehicule.update')->name('update');
            Route::delete('/{id}', 'destroy')->middleware('permission:vehicule.delete')->name('destroy');
        });

        Route::get('vehicules/{vehicule}/plaque/{plaque}/edit', [PlaqueController::class, 'edit'])
            ->middleware('permission:plaque.update')->name('plaques.edit');
        Route::put('vehicules/{vehicule}/plaque/{plaque}', [PlaqueController::class, 'update'])
            ->middleware('permission:plaque.update')->name('plaques.update');
        Route::get('vehicules/{vehicule}/carte-rose/{carteRose}/edit', [CarteRoseController::class, 'edit'])
            ->middleware('permission:carte_rose.update')->name('carte-roses.edit');
        Route::put('vehicules/{vehicule}/carte-rose/{carteRose}', [CarteRoseController::class, 'update'])
            ->middleware('permission:carte_rose.update')->name('carte-roses.update');

        // Postes de travail
        Route::get('post-travails', [PostTravailController::class, 'index'])->middleware('permission:post_travail.view')->name('post-travails.index');
        Route::get('post-travails/create', [PostTravailController::class, 'create'])->middleware('permission:post_travail.create')->name('post-travails.create');
        Route::post('post-travails', [PostTravailController::class, 'store'])->middleware('permission:post_travail.create')->name('post-travails.store');
        Route::get('post-travails/{post_travail}/edit', [PostTravailController::class, 'edit'])->middleware('permission:post_travail.manage')->name('post-travails.edit');
        Route::put('post-travails/{post_travail}', [PostTravailController::class, 'update'])->middleware('permission:post_travail.manage')->name('post-travails.update');
        Route::patch('post-travails/{post_travail}', [PostTravailController::class, 'update'])->middleware('permission:post_travail.manage');
        Route::delete('post-travails/{post_travail}', [PostTravailController::class, 'destroy'])->middleware('permission:post_travail.manage')->name('post-travails.destroy');
        Route::get('post-travails/{post_travail}', [PostTravailController::class, 'show'])->middleware('permission:post_travail.view')->name('post-travails.show');

        // Produits des postes
        Route::get('post-produits', [PostProduitController::class, 'index'])->middleware('permission:post_produit.view')->name('post-produits.index');
        Route::get('post-produits/create', [PostProduitController::class, 'create'])->middleware('permission:post_produit.create')->name('post-produits.create');
        Route::post('post-produits', [PostProduitController::class, 'store'])->middleware('permission:post_produit.create')->name('post-produits.store');
        Route::get('post-produits/{post_produit}/edit', [PostProduitController::class, 'edit'])->middleware('permission:post_produit.manage')->name('post-produits.edit');
        Route::put('post-produits/{post_produit}', [PostProduitController::class, 'update'])->middleware('permission:post_produit.manage')->name('post-produits.update');
        Route::patch('post-produits/{post_produit}', [PostProduitController::class, 'update'])->middleware('permission:post_produit.manage');
        Route::delete('post-produits/{post_produit}', [PostProduitController::class, 'destroy'])->middleware('permission:post_produit.manage')->name('post-produits.destroy');
        Route::get('post-produits/{post_produit}', [PostProduitController::class, 'show'])->middleware('permission:post_produit.view')->name('post-produits.show');

        // Approvisionnements de produits
        Route::get('approvisionnements', [ApprovisionnementController::class, 'index'])->middleware('permission:approvisionnement.view')->name('approvisionnements.index');
        Route::get('approvisionnements/create', [ApprovisionnementController::class, 'create'])->middleware('permission:approvisionnement.create')->name('approvisionnements.create');
        Route::post('approvisionnements', [ApprovisionnementController::class, 'store'])->middleware('permission:approvisionnement.create')->name('approvisionnements.store');
        Route::get('approvisionnements/{id}', [ApprovisionnementController::class, 'show'])->middleware('permission:approvisionnement.view')->name('approvisionnements.show');
        Route::get('approvisionnements/{id}/edit', [ApprovisionnementController::class, 'edit'])->middleware('permission:approvisionnement.manage')->name('approvisionnements.edit');
        Route::put('approvisionnements/{id}', [ApprovisionnementController::class, 'update'])->middleware('permission:approvisionnement.manage')->name('approvisionnements.update');
        Route::delete('approvisionnements/{id}', [ApprovisionnementController::class, 'destroy'])->middleware('permission:approvisionnement.manage')->name('approvisionnements.destroy');
        Route::patch('approvisionnements/{id}/confirm', [ApprovisionnementController::class, 'confirm'])->middleware('permission:approvisionnement.confirm')->name('approvisionnements.confirm');

        // Produits
        Route::get('produits', [ProduitController::class, 'index'])->middleware('permission:produit.view')->name('produits.index');
        Route::get('produits/create', [ProduitController::class, 'create'])->middleware('permission:produit.create')->name('produits.create');
        Route::post('produits', [ProduitController::class, 'store'])->middleware('permission:produit.create')->name('produits.store');
        Route::get('produits/{produit}/edit', [ProduitController::class, 'edit'])->middleware('permission:produit.manage')->name('produits.edit');
        Route::put('produits/{produit}', [ProduitController::class, 'update'])->middleware('permission:produit.manage')->name('produits.update');
        Route::patch('produits/{produit}', [ProduitController::class, 'update'])->middleware('permission:produit.manage');
        Route::delete('produits/{produit}', [ProduitController::class, 'destroy'])->middleware('permission:produit.manage')->name('produits.destroy');
        Route::get('produits/{produit}', [ProduitController::class, 'show'])->middleware('permission:produit.view')->name('produits.show');

        // Propriétaires
        Route::get('proprietaires', [PropietaireController::class, 'index'])->middleware('permission:proprietaire.view')->name('proprietaires.index');
        Route::get('proprietaires/create', [PropietaireController::class, 'create'])->middleware('permission:proprietaire.create')->name('proprietaires.create');
        Route::post('proprietaires', [PropietaireController::class, 'store'])->middleware('permission:proprietaire.create')->name('proprietaires.store');
        Route::get('proprietaires/{proprietaire}/edit', [PropietaireController::class, 'edit'])->middleware('permission:proprietaire.manage')->name('proprietaires.edit');
        Route::put('proprietaires/{proprietaire}', [PropietaireController::class, 'update'])->middleware('permission:proprietaire.manage')->name('proprietaires.update');
        Route::patch('proprietaires/{proprietaire}', [PropietaireController::class, 'update'])->middleware('permission:proprietaire.manage');
        Route::delete('proprietaires/{proprietaire}', [PropietaireController::class, 'destroy'])->middleware('permission:proprietaire.manage')->name('proprietaires.destroy');
        Route::get('proprietaires/{proprietaire}', [PropietaireController::class, 'show'])->middleware('permission:proprietaire.view')->name('proprietaires.show');

        // Utilisateurs
        Route::get('users', [UserController::class, 'index'])->middleware(['permission:user.view', 'role:admin'])->name('users.index');
        Route::get('users/create', [UserController::class, 'create'])->middleware('permission:user.create')->name('users.create');
        Route::post('users', [UserController::class, 'store'])->middleware('permission:user.create')->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->middleware('permission:user.manage')->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->middleware('permission:user.manage')->name('users.update');
        Route::patch('users/{user}', [UserController::class, 'update'])->middleware('permission:user.manage');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->middleware('permission:user.manage')->name('users.destroy');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->middleware(['permission:user.manage', 'role:admin'])->name('users.password.reset');
        Route::get('users/{user}', [UserController::class, 'show'])->middleware('permission:user.view')->name('users.show');

        // Rôles & permissions (admin)
        Route::middleware('permission:role.manage')->group(function () {
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
            Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
            Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
            Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
            Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show');
            Route::put('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->name('roles.permissions.sync');
        });

        // Cartes roses
        Route::get('carte-roses', [CarteRoseController::class, 'index'])->middleware('permission:carte_rose.view')->name('carte-roses.index');
        Route::get('carte-roses/create', [CarteRoseController::class, 'create'])->middleware('permission:carte_rose.create')->name('carte-roses.create');
        Route::post('carte-roses', [CarteRoseController::class, 'store'])->middleware('permission:carte_rose.create')->name('carte-roses.store');
        Route::get('carte-roses/{carte_rose}', [CarteRoseController::class, 'show'])->middleware('permission:carte_rose.view')->name('carte-roses.show');
        Route::get('/carte/{id}', [CarteRoseController::class, 'imprimerCarte'])->middleware('permission:carte_rose.print')->name('carte.imprimer');

        // Plaques
        Route::get('plaques', [PlaqueController::class, 'index'])->middleware('permission:plaque.view')->name('plaques.index');
        Route::get('plaques/create', [PlaqueController::class, 'create'])->middleware('permission:plaque.create')->name('plaques.create');
        Route::post('plaques', [PlaqueController::class, 'store'])->middleware('permission:plaque.create')->name('plaques.store');
        Route::get('plaques/{plaque}', [PlaqueController::class, 'show'])->middleware('permission:plaque.view')->name('plaques.show');
    });
});
