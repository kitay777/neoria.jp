<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="overscroll-none">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/alpinejs" defer></script>

</head>
<body class="font-sans antialiased min-h-screen bg-gray-100 text-gray-900" x-data="{ open: false }" class="overscroll-none">
    
    
    <div class="flex flex-col min-h-screen">
        <!-- Navigation -->
        <!--include('layouts.navigation')-->

        <!-- Page Heading -->
        @isset($header)
            <header class="shadow bg-white">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Main Content -->
        <main class="flex-1 pb-14">
                @auth
                @else
                <div class="w-full">
                    <img src="/imgs/neoriabaner.png" alt="Line Separator" class="w-full">
                </div>
                <div class="text-center">
                    <a href="{{ route('register') }}" class="w-full inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-6 shadow">
                        会員登録はこちら
                    </a>
                </div>
                @endauth
            {{ $slot }}
        </main>
        
        <footer class="fixed bottom-0 left-0 right-0 h-14 bg-white border-t shadow-inner z-50">

            <div class="flex justify-around items-center h-14 text-xs text-gray-600">
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center hover:text-blue-600">
                    <x-heroicon-o-home class="w-6 h-6" />
                    <span>HOME</span>
                </a>
                <a href="{{ route('dashboard') }}" class="flex flex-col items-center hover:text-blue-600">
                    <x-heroicon-o-magnifying-glass class="w-6 h-6" />
                    <span>検索</span>
                </a>
                <a href="{{ route('works.create') }}" class="flex flex-col items-center hover:text-blue-600">
                    <x-heroicon-o-plus-circle class="w-6 h-6" />
                    <span>登録</span>
                </a>
                <a href="{{ route('works.index') }}" class="flex flex-col items-center hover:text-blue-600">
                    <x-heroicon-o-briefcase class="w-6 h-6" />
                    <span>一覧</span>
                </a>
                <a href="#" @click="open = true" class="flex flex-col items-center hover:text-blue-600">
                    <x-heroicon-o-user class="w-6 h-6" />
                    <span>マイページ</span>
                </a>
            </div>
        </footer>


    </div>
    
    <!-- Slide-in My Page Panel -->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 w-80 bg-[#AEECE4]/90 shadow-xl z-50 transform"
            @click.away="open = false"
            @keydown.escape.window="open = false"
            style="display: none;"
        >
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-lg font-bold">マイページ</h2>
            <button @click="open = false" class="text-gray-500 hover:text-red-500 text-xl">&times;</button>
        </div>

        <div class="p-4 space-y-4">
            <a href="{{ route('profile.edit') }}" class="block text-gray-700 hover:text-blue-600">プロフィール編集</a>
            <a href="{{ route('applications.mine') }}" class="block text-gray-700 hover:text-blue-600">応募履歴</a>
            <a href="{{ route('works.index') }}" class="block text-gray-700 hover:text-blue-600">仕事一覧</a>
            <a href="{{ route('points.history') }}" class="block text-gray-700 hover:text-blue-600">ポイント履歴</a>

            <!-- ログアウト -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-left text-gray-700 hover:text-red-600">
                    ログアウト
                </button>
            </form>
        </div>
    </div>

</body>
</html>
