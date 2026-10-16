<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SalesRecordController;
use App\Http\Controllers\RandomForestController;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route(
        auth()->check() ? 'dashboard.redirect' : 'login'
    );
});

/*
|--------------------------------------------------------------------------
| Authentication - Guest Only
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.process');

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.process');
});

/*
|--------------------------------------------------------------------------
| Dashboard Redirect
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [
    AuthController::class,
    'redirectToDashboard',
])->middleware('auth')->name('dashboard.redirect');

/*
|--------------------------------------------------------------------------
| Input Produk dan Penjualan
| Admin, Petugas, dan User
|--------------------------------------------------------------------------
| Letakkan sebelum route dinamis /sales-records/{salesRecord}.
*/

Route::middleware([
    'auth',
    RoleMiddleware::class . ':admin,petugas,user',
])->group(function () {

    // Form tambah produk
    Route::get('/products/create', [
        ProductController::class,
        'create',
    ])->name('products.create');

    // Simpan produk
    Route::post('/products', [
        ProductController::class,
        'store',
    ])->name('products.store');

    // Form tambah penjualan
    Route::get('/sales-records/create', [
        SalesRecordController::class,
        'create',
    ])->name('sales-records.create');

    // Simpan penjualan
    Route::post('/sales-records', [
        SalesRecordController::class,
        'store',
    ])->name('sales-records.store');
});

/*
|--------------------------------------------------------------------------
| Admin - Semua Fitur
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    RoleMiddleware::class . ':admin',
])->group(function () {

    Route::view('/admin/dashboard', 'admin.dashboard')
        ->name('admin.dashboard');

    /*
    | Manajemen Pengguna - Admin Only
    */

    Route::prefix('admin/users')
        ->name('admin.users.')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{id}/edit', 'edit')->name('edit');
            Route::put('/{id}', 'update')->name('update');
            Route::delete('/{id}', 'destroy')->name('destroy');
        });
});

/*
|--------------------------------------------------------------------------
| Operasional - Admin dan Petugas
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    RoleMiddleware::class . ':admin,petugas',
])->group(function () {

    /*
    | Dashboard Petugas
    */

    Route::view('/petugas/dashboard', 'petugas.dashboard')
        ->name('petugas.dashboard');

    /*
    | Negara
    */

    Route::resource('countries', CountryController::class);

    /*
    | Produk
    | Create dan store sudah didefinisikan di grup input bersama.
    */

    Route::get('/products', [
        ProductController::class,
        'index',
    ])->name('products.index');

    Route::get('/products/{product}/edit', [
        ProductController::class,
        'edit',
    ])->name('products.edit');

    Route::get('/products/{product}', [
        ProductController::class,
        'show',
    ])->whereNumber('product')->name('products.show');

    Route::put('/products/{product}', [
        ProductController::class,
        'update',
    ])->whereNumber('product')->name('products.update');

    Route::patch('/products/{product}', [
        ProductController::class,
        'update',
    ])->whereNumber('product')->name('products.update.patch');

    Route::delete('/products/{product}', [
        ProductController::class,
        'destroy',
    ])->whereNumber('product')->name('products.destroy');

    /*
    | Data Penjualan
    | Create dan store sudah didefinisikan di grup input bersama.
    */

    Route::post('/sales-records/recalculate', [
        SalesRecordController::class,
        'recalculate',
    ])->name('sales-records.recalculate');

    Route::get('/sales-records', [
        SalesRecordController::class,
        'index',
    ])->name('sales-records.index');

    Route::get('/sales-records/{salesRecord}/edit', [
        SalesRecordController::class,
        'edit',
    ])->whereNumber('salesRecord')->name('sales-records.edit');

    Route::get('/sales-records/{salesRecord}', [
        SalesRecordController::class,
        'show',
    ])->whereNumber('salesRecord')->name('sales-records.show');

    Route::put('/sales-records/{salesRecord}', [
        SalesRecordController::class,
        'update',
    ])->whereNumber('salesRecord')->name('sales-records.update');

    Route::patch('/sales-records/{salesRecord}', [
        SalesRecordController::class,
        'update',
    ])->whereNumber('salesRecord')->name('sales-records.update.patch');

    Route::delete('/sales-records/{salesRecord}', [
        SalesRecordController::class,
        'destroy',
    ])->whereNumber('salesRecord')->name('sales-records.destroy');

    /*
    | Analisis dan Random Forest
    */

    Route::get('/sales-analysis', [
        RandomForestController::class,
        'index',
    ])->name('random-forest.index');

    Route::post('/random-forest/train', [
        RandomForestController::class,
        'train',
    ])->name('random-forest.train');

    Route::post('/random-forest/predict/{salesRecord}', [
        RandomForestController::class,
        'predict',
    ])->whereNumber('salesRecord')->name('random-forest.predict');
});

/*
|--------------------------------------------------------------------------
| User Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    RoleMiddleware::class . ':user',
])->group(function () {

    Route::view('/user/dashboard', 'user.dashboard')
        ->name('user.dashboard');
});

/*
|--------------------------------------------------------------------------
| Profil - Semua Pengguna yang Login
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/profile/edit', [
        UserController::class,
        'editProfile',
    ])->name('profile.edit');

    Route::put('/profile/update', [
        UserController::class,
        'updateProfile',
    ])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [
    AuthController::class,
    'logout',
])->middleware('auth')->name('logout');
