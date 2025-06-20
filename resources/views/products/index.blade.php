@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Tab Navigation -->
    <div class="flex space-x-8 mb-8 border-b">
        <a href="{{ route('products.index') }}" 
           class="pb-4 {{ !$isMyList ? 'border-b-2 border-red-500 text-red-500' : 'text-gray-500 hover:text-gray-700' }}">
            おすすめ
        </a>
        @auth
            <a href="{{ route('products.index', ['page' => 'mylist']) }}" 
               class="pb-4 {{ $isMyList ? 'border-b-2 border-red-500 text-red-500' : 'text-gray-500 hover:text-gray-700' }}">
                マイリスト
            </a>
        @endauth
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
        @forelse($products as $product)
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
                    @if($isMyList)
                        マイリストに商品がありません
                    @else
                        商品がありません
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-8">
            {{ $products->appends(request()->query())->links() }}
        </div>
    @endif
</div>
@endsection
