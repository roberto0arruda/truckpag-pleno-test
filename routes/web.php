<?php

use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'status']);

Route::apiResource('products', ProductController::class)
    ->withoutMiddleware([VerifyCsrfToken::class])
    ->except('store');
