<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/latest-prices', 'App\Http\Controllers\Api\StockPricesController@latest')
    ->name('stock-prices.latest');
