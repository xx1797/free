@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="aspect-square relative overflow-hidden rounded-lg bg-gray-100">
            @if($product->image_path)
                <img src="{{ asset('storage/' . $product->image_path) }}" 
                     alt="{{ $product->name }}" 
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="text-gray-400">No Image</span>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                <p class="text-sm text-gray-500 mb-4">{{ $product->brand ?? 'ブランド名' }}</p>
                <p class="text-3xl font-bold text-red-600">¥{{ number_format($product->price) }}（税込）</p>
            </div>

            <!-- Like and Comment Icons -->
            <div class="flex items-center space-x-6">
                @auth
                    <button onclick="toggleLike({{ $product->id }})" 
                            class="flex items-center space-x-2 text-gray-600 hover:text-red-500 transition-colors">
                        <svg id="like-icon-{{ $product->id }}" class="w-6 h-6 {{ $isLiked ? 'text-red-500 fill-current' : '' }}" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span id="like-count-{{ $product->id }}">{{ $product->likes->count() }}</span>
                    </button>
                @else
                    <div class="flex items-center space-x-2 text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span>{{ $product->likes->count() }}</span>
                    </div>
                @endauth
                
                <div class="flex items-center space-x-2 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <span>{{ $product->comments->count() }}</span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-4">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">商品説明</h3>
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 mb-2">商品の情報</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">カテゴリー</span>
                            <span>
                                @foreach($product->categories as $category)
                                    {{ $category->name }}@if(!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">商品の状態</span>
                            <span>{{ $product->condition->name }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Button -->
            @auth
                @if(!$product->is_sold && $product->user_id !== Auth::id())
                    <a href="{{ route('orders.create', $product->id) }}" 
                       class="block w-full bg-red-500 hover:bg-red-600 text-white text-center py-3 rounded-lg font-semibold transition-colors">
                        購入手続きへ
                    </a>
                @elseif($product->is_sold)
                    <div class="w-full bg-gray-400 text-white text-center py-3 rounded-lg font-semibold">
                        売り切れました
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}" 
                   class="block w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-3 rounded-lg font-semibold transition-colors">
                    ログインして購入
                </a>
            @endauth
        </div>
    </div>

    <!-- Comments Section -->
    <div class="mt-12">
        <h3 class="text-xl font-bold mb-6">コメント ({{ $product->comments->count() }})</h3>
        
        @auth
            <!-- Comment Form -->
            <form action="{{ route('comments.store', $product->id) }}" method="POST" class="mb-8">
                @csrf
                <div class="mb-4">
                    <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">商品へのコメント</label>
                    <textarea id="comment" name="comment" rows="4" 
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500"
                              placeholder="コメントを入力してください">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                    コメントを送信する
                </button>
            </form>
        @endauth

        <!-- Comments List -->
        <div class="space-y-4">
            @forelse($product->comments as $comment)
                <div class="border-b border-gray-200 pb-4">
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            @if($comment->user->avatar_path)
                                <img src="{{ asset('storage/' . $comment->user->avatar_path) }}" 
                                     alt="{{ $comment->user->name }}" 
                                     class="w-10 h-10 rounded-full object-cover">
                            @else
                                <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                    <span class="text-gray-600 text-sm">{{ substr($comment->user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                <span class="text-sm text-gray-500">{{ $comment->created_at->format('Y/m/d H:i') }}</span>
                            </div>
                            <p class="text-gray-700">{{ $comment->comment }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-8">まだコメントがありません</p>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleLike(productId) {
    fetch(`/api/products/${productId}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const icon = document.getElementById(`like-icon-${productId}`);
        const count = document.getElementById(`like-count-${productId}`);
        
        if (data.isLiked) {
            icon.classList.add('text-red-500', 'fill-current');
        } else {
            icon.classList.remove('text-red-500', 'fill-current');
        }
        
        count.textContent = data.likeCount;
    });
}
</script>
@endpush
@endsection
