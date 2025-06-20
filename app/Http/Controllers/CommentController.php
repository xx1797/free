<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // FN020: コメント送信機能
    public function store(CommentRequest $request, $item_id)
    {
        $product = Product::findOrFail($item_id);
        
        $product->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        
        return redirect()->route('products.show', $item_id)
                        ->with('success', 'コメントを投稿しました');
    }
}
