<?php

use App\Http\Controllers\Api\AccessTokensController;
use App\Http\Controllers\Api\DeviceTokensController;
use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/tokens', [AccessTokensController::class, 'store']);
Route::delete('auth/tokens', [AccessTokensController::class, 'destroy'])
    ->middleware('auth:sanctum');

// categories
Route::apiResource('categories', 'Api\CategoriesController');

//product
Route::apiResource('product', 'Api\ProductController');

//brand
Route::apiResource('brand', 'Api\BrandController');

//shipping
Route::apiResource('shipping', 'Api\ShippingController');

//users
Route::apiResource('users', 'Api\UsersCntroller');
