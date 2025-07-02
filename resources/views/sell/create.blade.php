@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-2xl font-bold mb-6">商品の出品</h1>
                
                <form method="POST" action="{{ route('sell.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-2">商品画像</label>
                        <input type="file" id="image" name="image" accept=".jpeg,.png"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">商品名</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">ブランド名</label>
                        <input type="text" id="brand" name="brand" value="{{ old('brand') }}"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('brand')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">商品説明</label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">カテゴリー</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($categories as $category)
                                <label class="flex items-center">
                                    <input type="checkbox" name="categories[]" value="{{ $category->id }}" 
                                           {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                           class="mr-2">
                                    {{ $category->name }}
                                </label>
                            @endforeach
                        </div>
                        @error('categories')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="condition_id" class="block text-sm font-medium text-gray-700 mb-2">商品の状態</label>
                        <select id="condition_id" name="condition_id"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <option value="">選択してください</option>
                            @foreach($conditions as $condition)
                                <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>
                                    {{ $condition->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('condition_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="mb-6">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">販売価格</label>
                        <input type="number" id="price" name="price" value="{{ old('price') }}" min="0"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                        @error('price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-red-500 text-white py-3 rounded-lg hover:bg-red-600 transition-colors font-semibold">
                        出品する
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
