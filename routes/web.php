<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PostController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['guest']], function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
});

Route::group(['middleware' => ['auth']], function () {
    Route::group([
        'middleware' => 'role:admin',
        'prefix' => 'admin',
        'as' => 'admin.'
    ], function () {
        Route::group([
            'prefix' => 'dokter',
            'as' => 'dokter.'
        ], function () {
            Route::get('/', [DokterController::class, 'index'])->name('index');
            Route::post('/', [DokterController::class, 'store'])->name('store');
            Route::put('/{id}', [DokterController::class, 'update'])->name('update');
            Route::get('/{id}', [DokterController::class, 'edit'])->name('edit');
            Route::delete('/{id}', [DokterController::class, 'destroy'])->name('destroy');
        });

        Route::group([
            'prefix' => 'post',
            'as' => 'post.'
        ], function () {
            Route::get('/', [PostController::class, 'index'])->name('index');
            Route::post('/', [PostController::class, 'store'])->name('store');
            Route::get('/check-slug', [PostController::class, 'checkSlug'])->name('checkSlug');
            Route::put('/{id}', [PostController::class, 'update'])->name('update');
            Route::get('/{id}', [PostController::class, 'edit'])->name('edit');
            Route::delete('/{id}', [PostController::class, 'destroy'])->name('destroy');
        });

        Route::group([
            'prefix' => 'category',
            'as' => 'category.'
        ], function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/check-slug', [CategoryController::class, 'checkSlug'])->name('checkSlug');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::get('/{id}', [CategoryController::class, 'edit'])->name('edit');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        Route::group([
            'prefix' => 'pasien',
            'as' => 'pasien.'
        ], function () {
            Route::get('/', [PasienController::class, 'index'])->name('index');
            Route::put('/status', [PasienController::class, 'changeStatus'])->name('changeStatus');
            Route::delete('/{id}', [PasienController::class, 'destroy'])->name('destroy');
        });
    });
    Route::get('/logout', [LogoutController::class, 'perform'])->name('logout.perform');
});

Route::get('/send-event', function () {
    $message = "udisn saparudins";
    broadcast(new \App\Events\HelloEvent($message));
});
