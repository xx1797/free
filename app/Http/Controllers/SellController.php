<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExhibitionRequest;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condition;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();
        
        return view('sell.create', compact('categories', 'conditions'));
    }

    // FN028: 出品商品情報登録機能, FN029: 出品商品画像アップロード機能
    public function store(ExhibitionRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $product = Product::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'condition_id' => $request->condition_id,
            'price' => $request->price,
            'image_path' => $imagePath,
        ]);

        // カテゴリの複数選択対応
        $product->categories()->attach($request->categories);

        return redirect()->route('products.index')->with('success', '商品を出品しました');
    }
}
