<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('/products/{item_id}/like', [ProductController::class, 'toggleLike']);
});
