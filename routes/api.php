<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('test', function (){
    $route = Route::getCurrentRoute();
    dd($route->gatherMiddleware());
});

Route::prefix('v1')->group(function() {
    Route::prefix('auth')->group(function() {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login',    [AuthController::class, 'login']);
        Route::middleware('auth:api')->group(function() {
            Route::post('logout',   [AuthController::class, 'logout']);
            Route::post('refresh',  [AuthController::class, 'refresh']);
            Route::post('me',       [AuthController::class, 'me']);
        });
    });
    Route::middleware(['auth:api', 'role:admin'])->group(function() {
        Route::apiResource(
            'products',
            ProductController::class,
            ['only' => ['store', 'update', 'destroy']]
        );
        Route::apiResource(
            'categories',
            CategoryController::class,
            ['only' => ['store', 'update', 'destroy']]
        );
    });
    Route::middleware(['auth:api'])->group(function() {
        Route::apiResource('orders', OrderController::class);
    });
    Route::apiResource(
        'products',
        ProductController::class,
        ['only' => ['index', 'show']]
    );
    Route::apiResource(
        'categories',
        CategoryController::class,
        ['only' => ['index', 'show']]
    );
});
