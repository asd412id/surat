<?php

use App\Http\Controllers\JenisSuratController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [UserController::class, 'login'])->name('login');
    Route::post('/login', [UserController::class, 'loginProcess'])->name('login.process');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'home'])->name('home');
    Route::get('/account', [UserController::class, 'account'])->name('account');
    Route::post('/account', [UserController::class, 'accountUpdate'])->name('account.update');
    Route::get('/logout', [UserController::class, 'logout'])->name('logout');
    Route::post('/logout', [UserController::class, 'logoutProcess'])->name('logout.process');

    Route::middleware('role:0')->group(function () {
        Route::get('/search/jenis-surat', [SearchController::class, 'jenisSurat'])->name('search.jenis-surat');
        Route::get('/search/nomor', [SearchController::class, 'nomorSurat'])->name('search.nomor');

        Route::resource('user', UserController::class);
        Route::get('/user/{user}/delete', [UserController::class, 'delete'])->name('user.delete');

        Route::resource('jenis-surat', JenisSuratController::class);
        Route::get('/jenis-surat/{jenis_surat}/delete', [JenisSuratController::class, 'delete'])->name('jenis-surat.delete');

        Route::resource('surat', SuratController::class);
        Route::get('/surat/{surat}/delete', [SuratController::class, 'delete'])->name('surat.delete');
        Route::get('/surat/{surat}/download', [SuratController::class, 'download'])->name('surat.download');
        Route::get('/print', [SuratController::class, 'print'])->name('surat.print');
        Route::post('/print', [SuratController::class, 'printProcess'])->name('surat.print.process');
    });
});
