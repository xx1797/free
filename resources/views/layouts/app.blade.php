<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'COACHTECH') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-black text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('products.index') }}" class="flex items-center">
                            <img src="{{ asset('images/logo.svg') }}" alt="COACHTECH" class="h-8">
                        </a>
                    </div>

                    <!-- Search Bar (Desktop) -->
                    <div class="hidden md:flex flex-1 max-w-lg mx-8">
                        <form action="{{ route('products.index') }}" method="GET" class="w-full">
                            <input type="text" name="search" placeholder="なにをお探しですか？" 
                                   class="w-full px-4 py-2 rounded-md text-black focus:outline-none focus:ring-2 focus:ring-red-500">
                        </form>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex items-center space-x-6">
                        @auth
                            <a href="{{ route('products.index', ['page' => 'mylist']) }}" 
                               class="hover:text-gray-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                            </a>
                            <a href="{{ route('sell.create') }}" 
                               class="bg-red-500 hover:bg-red-600 px-4 py-2 rounded-md transition-colors">
                                出品
                            </a>
                            <a href="{{ route('profile.show') }}" class="hover:text-gray-300 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hover:text-gray-300 transition-colors">ログイン</a>
                            <a href="{{ route('register') }}" class="hover:text-gray-300 transition-colors">会員登録</a>
                        @endauth
                    </nav>
                </div>

                <!-- Mobile Search Bar -->
                <div class="md:hidden pb-4">
                    <form action="{{ route('products.index') }}" method="GET">
                        <input type="text" name="search" placeholder="なにをお探しですか？" 
                               class="w-full px-4 py-2 rounded-md text-black focus:outline-none focus:ring-2 focus:ring-red-500">
                    </form>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
