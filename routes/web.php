<?php

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

Route::get('/', [\App\Http\Controllers\HomeController::class, 'home']);

Route::view('/template', 'template');

Route::controller(\App\Http\Controllers\UserController::class)->group(function () {
    Route::get('/login', 'login')->middleware([\App\Http\Middleware\OnlyGuestMiddleware::class]);
    Route::post('/login', 'doLogin')->middleware([\App\Http\Middleware\OnlyGuestMiddleware::class]);
    Route::get('/register', 'register')->middleware([\App\Http\Middleware\OnlyGuestMiddleware::class]);
    Route::post('/register', 'doRegister')->middleware([\App\Http\Middleware\OnlyGuestMiddleware::class]);
    Route::post('/logout', 'doLogout')->middleware([\App\Http\Middleware\OnlyMemberMiddleware::class]);
});

Route::controller(\App\Http\Controllers\CustomerController::class)
    ->middleware([\App\Http\Middleware\OnlyMemberMiddleware::class])
    ->group(function () {
        Route::get("/beranda", 'home');
        Route::get("/edit-form", 'editForm');
        Route::get('/tambah-kustomer', 'createCustomer');
        Route::post('/tambah-kustomer', 'doCreateCustomer');
        Route::get('/kustomer/{id}/edit', 'editCustomer');
        Route::post('/kustomer/{id}/edit', 'doEditCustomer');
        Route::post('/kustomer/{id}/delete', 'removeCustomer');
    });
