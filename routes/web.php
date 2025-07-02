<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PG01: 商品一覧画面（トップ画面）
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// PG05: 商品詳細画面
Route::get('/item/{item_id}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    // PG06: 商品購入画面
    Route::get('/purchase/{item_id}', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/purchase/{item_id}', [OrderController::class, 'store'])->name('orders.store');
    
    // PG07: 送付先住所変更画面
    Route::get('/purchase/address/{item_id}', [OrderController::class, 'editAddress'])->name('orders.edit-address');
    Route::post('/purchase/address/{item_id}', [OrderController::class, 'updateAddress'])->name('orders.update-address');
    
    // PG08: 商品出品画面
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
    
    // PG09: プロフィール画面
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');
    
    // PG10: プロフィール編集画面（設定画面）
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // コメント機能
    Route::post('/item/{item_id}/comments', [CommentController::class, 'store'])->name('comments.store');
    
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
