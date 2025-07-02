<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // PG06: 商品購入画面
    public function create($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();
        
        // セッションから配送先住所を取得
        $shippingAddress = session('shipping_address', [
            'postal_code' => $user->postal_code,
            'address' => $user->address,
            'building' => $user->building,
        ]);
        
        return view('orders.create', compact('product', 'user', 'shippingAddress'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $product = Product::findOrFail($item_id);

        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'total_amount' => $product->price,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
            'status' => 'completed'
        ]);

        // 商品を売り切れ状態に更新
        $product->update(['is_sold' => true]);

        return redirect()->route('profile.show')->with('success', '購入が完了しました');
    }

    // PG07: 送付先住所変更画面
    public function editAddress($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();
        
        return view('orders.edit-address', compact('product', 'user'));
    }

    public function updateAddress(AddressRequest $request, $item_id)
    {
        // セッションに住所情報を保存
        session([
            'shipping_address' => [
                'name' => $request->name,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        return redirect()->route('orders.create', $item_id)
                        ->with('success', '配送先住所を変更しました');
    }
}
