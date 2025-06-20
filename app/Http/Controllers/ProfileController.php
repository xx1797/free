<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // PG09: プロフィール画面
    // PG11: プロフィール画面_購入した商品一覧
    // PG12: プロフィール画面_出品した商品一覧
    public function show(Request $request)
    {
        $user = Auth::user();
        $page = $request->get('page', 'sell'); // デフォルトは出品した商品
        
        if ($page === 'buy') {
            // PG11: 購入した商品一覧
            $items = Order::where('user_id', $user->id)
                         ->with('product')
                         ->latest()
                         ->paginate(12);
        } else {
            // PG12: 出品した商品一覧（デフォルト）
            $items = Product::where('user_id', $user->id)
                           ->latest()
                           ->paginate(12);
        }
        
        return view('profile.show', compact('user', 'items', 'page'));
    }

    // PG10: プロフィール編集画面（設定画面）
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();
        
        if ($request->hasFile('avatar')) {
            // 古い画像を削除
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_path = $avatarPath;
        }

        $user->update([
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        return redirect()->route('profile.show')->with('success', 'プロフィールを更新しました');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
