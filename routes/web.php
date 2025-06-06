<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('app');
});

Route::get('/products', function () {
    return view('app');
});

Route::get('/products/create', function () {
    return view('app');
});

Route::get('/products/{id}/edit', function () {
    return view('app');
});

Route::get('/login', function () {
    return view('app');
});

Route::get('/register', function () {
    return view('app');
});

Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');
