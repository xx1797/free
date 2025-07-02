@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">商品購入</h1>
                
                <div class="border-b pb-4 mb-6">
                    <h2 class="text-lg font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-xl font-semibold text-red-600">¥{{ number_format($product->price) }}</p>
                </div>
                
                <form method="POST" action="{{ route('orders.store', $product->id) }}">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">支払い方法</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="card" class="mr-2" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                カード支払い
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="convenience" class="mr-2" {{ old('payment_method') == 'convenience' ? 'checked' : '' }}>
                                コンビニ支払い
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_method" value="bank" class="mr-2" {{ old('payment_method') == 'bank' ? 'checked' : '' }}>
                                銀行振込
                            </label>
                        </div>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">郵便番号</label>
                        <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $shippingAddress['postal_code'] ?? '') }}"
                               placeholder="123-4567"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('postal_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">住所</label>
                        <input type="text" id="address" name="address" value="{{ old('address', $shippingAddress['address'] ?? '') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="building" class="block text-sm font-medium text-gray-700 mb-2">建物名</label>
                        <input type="text" id="building" name="building" value="{{ old('building', $shippingAddress['building'] ?? '') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('building')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <a href="{{ route('orders.edit-address', $product->id) }}" 
                           class="text-red-500 hover:text-red-600 text-sm">
                            配送先を変更する
                        </a>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition-colors font-semibold">
                        購入する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
