<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


// Route::get('product', [ProductController::class, 'index']);
// Route::post('product',[ProductController::class, 'store']);
// Route::get('product/{id}',[ProductController::class, 'show']);
// Route::put('product/{id}',[ProductController::class, 'update']);
// Route::delete('product/{id}',[ProductController::class, 'destroy']);

   Route::apiResource('product', ProductController::class );
