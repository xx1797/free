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
                
                <form method="POST" action="{{ route('orders.store') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="mb-4">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">数量</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('quantity')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">郵便番号</label>
                        <input type="text" id="postal_code" name="postal_code" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('postal_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">住所</label>
                        <input type="text" id="address" name="address" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="building" class="block text-sm font-medium text-gray-700 mb-2">建物名</label>
                        <input type="text" id="building" name="building" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('building')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
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
