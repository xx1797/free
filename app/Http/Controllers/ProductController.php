<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // FN014: 商品一覧取得, FN015: マイリスト一覧取得, FN016: 商品検索機能
    public function index(Request $request)
    {
        $search = $request->get('search');
        $page = $request->get('page');
        
        $query = Product::with(['user', 'likes', 'categories', 'condition'])
                       ->search($search);
        
        if ($page === 'mylist' && Auth::check()) {
            // FN015: マイリスト一覧取得
            $query->whereHas('likes', function($q) {
                $q->where('user_id', Auth::id());
            });
        } else {
            // FN014: 商品一覧取得 - 自分が出品した商品は表示されない
            if (Auth::check()) {
                $query->excludeOwn(Auth::id());
            }
        }
        
        $products = $query->latest()->paginate(20);
        $isMyList = $page === 'mylist';
        
        return view('products.index', compact('products', 'isMyList', 'search'));
    }

    // FN017: 商品詳細情報取得
    public function show($item_id)
    {
        $product = Product::with(['user', 'likes', 'comments.user', 'categories', 'condition'])
                         ->findOrFail($item_id);
        
        $isLiked = Auth::check() ? $product->likes()->where('user_id', Auth::id())->exists() : false;
        
        return view('products.show', compact('product', 'isLiked'));
    }

    // FN018: いいね機能
    public function toggleLike(Request $request, $item_id)
    {
        $product = Product::findOrFail($item_id);
        $like = $product->likes()->where('user_id', Auth::id())->first();
        
        if ($like) {
            $like->delete();
            $isLiked = false;
        } else {
            $product->likes()->create(['user_id' => Auth::id()]);
            $isLiked = true;
        }
        
        $likeCount = $product->likes()->count();
        
        return response()->json([
            'isLiked' => $isLiked,
            'likeCount' => $likeCount
        ]);
    }
}
