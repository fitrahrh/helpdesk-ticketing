<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExampleController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changepassword'])->name('profile.change-password');
    Route::put('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::get('/blank-page', [App\Http\Controllers\HomeController::class, 'blank'])->name('blank');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Role management routes
    Route::get('/roles', [App\Http\Controllers\Roles\RoleController::class, 'index'])->name('roles.index');
    Route::get('/dataroles', [App\Http\Controllers\Roles\RoleController::class, 'dataroles'])->name('roles.data');
    Route::post('/roles', [App\Http\Controllers\Roles\RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{id}/edit', [App\Http\Controllers\Roles\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{id}', [App\Http\Controllers\Roles\RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{id}', [App\Http\Controllers\Roles\RoleController::class, 'destroy'])->name('roles.destroy');

    // User management routes
    Route::get('/users', [App\Http\Controllers\Users\UserController::class, 'index'])->name('users.index');
    Route::get('/datausers', [App\Http\Controllers\Users\UserController::class, 'datausers'])->name('users.data');
    Route::post('/users', [App\Http\Controllers\Users\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [App\Http\Controllers\Users\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [App\Http\Controllers\Users\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [App\Http\Controllers\Users\UserController::class, 'destroy'])->name('users.destroy');

    // Skpd management routes
    Route::get('/skpd', [App\Http\Controllers\Skpds\SKPDController::class, 'index'])->name('skpd.index');
    Route::get('/skpd/data', [App\Http\Controllers\Skpds\SKPDController::class, 'dataskpd'])->name('skpd.data');
    Route::post('/skpd', [App\Http\Controllers\Skpds\SKPDController::class, 'store'])->name('skpd.store');
    Route::get('/skpd/{id}/edit', [App\Http\Controllers\Skpds\SKPDController::class, 'edit'])->name('skpd.edit');
    Route::put('/skpd/{id}', [App\Http\Controllers\Skpds\SKPDController::class, 'update'])->name('skpd.update');
    Route::delete('/skpd/{id}', [App\Http\Controllers\Skpds\SKPDController::class, 'destroy'])->name('skpd.destroy');

    // Bidang management routes
    Route::get('/bidang', [App\Http\Controllers\Bidangs\BidangController::class, 'index'])->name('bidang.index');
    Route::get('/bidang/data', [App\Http\Controllers\Bidangs\BidangController::class, 'databidang'])->name('bidang.data');
    Route::post('/bidang', [App\Http\Controllers\Bidangs\BidangController::class, 'store'])->name('bidang.store');
    Route::get('/bidang/{id}/edit', [App\Http\Controllers\Bidangs\BidangController::class, 'edit'])->name('bidang.edit');
    Route::put('/bidang/{id}', [App\Http\Controllers\Bidangs\BidangController::class, 'update'])->name('bidang.update');
    Route::delete('/bidang/{id}', [App\Http\Controllers\Bidangs\BidangController::class, 'destroy'])->name('bidang.destroy');

    // Jabatan management routes
    Route::get('/jabatan', [App\Http\Controllers\Jabatans\JabatanController::class, 'index'])->name('jabatan.index');
    Route::get('/jabatan/data', [App\Http\Controllers\Jabatans\JabatanController::class, 'datajabatan'])->name('jabatan.data');
    Route::post('/jabatan', [App\Http\Controllers\Jabatans\JabatanController::class, 'store'])->name('jabatan.store');
    Route::get('/jabatan/{id}/edit', [App\Http\Controllers\Jabatans\JabatanController::class, 'edit'])->name('jabatan.edit');
    Route::put('/jabatan/{id}', [App\Http\Controllers\Jabatans\JabatanController::class, 'update'])->name('jabatan.update');
    Route::delete('/jabatan/{id}', [App\Http\Controllers\Jabatans\JabatanController::class, 'destroy'])->name('jabatan.destroy');

    Route::get('/get-bidang/{skpd_id}', [App\Http\Controllers\Users\UserController::class, 'getBidangBySkpd']);
    Route::get('/get-jabatan/{bidang_id}', [App\Http\Controllers\Users\UserController::class, 'getJabatanByBidang']);
    Route::get('/get-jabatan-info/{jabatan_id}', [App\Http\Controllers\Users\UserController::class, 'getJabatanInfo']);

    Route::get('/kategori', [App\Http\Controllers\KategoriController\KategoriController::class, 'index'])->name('kategori.index');
    Route::get('/kategori/data', [App\Http\Controllers\KategoriController\KategoriController::class, 'datakategori'])->name('kategori.data');
    Route::post('/kategori', [App\Http\Controllers\KategoriController\KategoriController::class, 'store'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\KategoriController\KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('/kategori/{id}', [App\Http\Controllers\KategoriController\KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('/kategori/{id}', [App\Http\Controllers\KategoriController\KategoriController::class, 'destroy'])->name('kategori.destroy');
});



