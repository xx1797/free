@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- User Profile Section -->
    <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                @if($user->avatar_path)
                    <img src="{{ asset('storage/' . $user->avatar_path) }}" 
                         alt="{{ $user->name }}" 
                         class="w-16 h-16 rounded-full object-cover">
                @else
                    <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-gray-600 text-xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <a href="{{ route('profile.edit') }}" 
                   class="inline-block mt-2 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                    プロフィールを編集
                </a>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex space-x-8 mb-8 border-b">
        <a href="{{ route('profile.show') }}" 
           class="pb-4 {{ $page === 'sell' ? 'border-b-2 border-red-500 text-red-500' : 'text-gray-500 hover:text-gray-700' }}">
            出品した商品
        </a>
        <a href="{{ route('profile.show', ['page' => 'buy']) }}" 
           class="pb-4 {{ $page === 'buy' ? 'border-b-2 border-red-500 text-red-500' : 'text-gray-500 hover:text-gray-700' }}">
            購入した商品
        </a>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
        @forelse($items as $item)
            @php
                $product = $page === 'buy' ? $item->product : $item;
            @endphp
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                <a href="{{ route('products.show', $product->id) }}" class="block">
                    <div class="aspect-square relative overflow-hidden rounded-t-lg">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-sm">No Image</span>
                            </div>
                        @endif
                        
                        @if($product->is_sold)
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center">
                                <span class="bg-red-500 text-white px-3 py-1 rounded text-sm font-semibold">
                                    SOLD
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-3">
                        <h3 class="text-sm font-medium text-gray-900 mb-1 line-clamp-2">{{ $product->name }}</h3>
                        <p class="text-lg font-bold text-gray-900">¥{{ number_format($product->price) }}</p>
                    </div>
                </a>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <p class="text-gray-500">
                    @if($page === 'buy')
                        購入した商品がありません
                    @else
                        出品した商品がありません
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="mt-8">
            {{ $items->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
