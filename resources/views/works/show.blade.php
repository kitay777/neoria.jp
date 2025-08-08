<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('仕事詳細') }}
        </h2>
    </x-slot>

    <div class="relative w-full">
        {{-- 背景画像 --}}
        <img src="{{ $work->image_path ? asset('storage/' . $work->image_path) : asset('/imgs/noimage.png') }}"
            alt="仕事画像"
            class="w-full h-[250px] object-cover">

        {{-- 丸い画像＋名前を下中央に表示 --}}
        <div class="absolute -bottom-10 left-1/2 transform -translate-x-1/2">
            <div class="flex items-end space-x-4">
                {{-- 背景付き丸顔 --}}
                <div class="w-20 h-20 rounded-full bg-white/70 backdrop-blur-md flex items-center justify-center shadow-lg">
                    <img src="{{ $work->user->profile_photo_path
                                ? asset('storage/' . $work->user->profile_photo_path)
                                : asset('/imgs/noimage.png') }}"
                        alt="プロフィール画像"
                        class="w-20 h-20 rounded-full object-cover border-2 border-white">
                </div>

                {{-- 名前（画像と下揃え） --}}
                <div class="pb-2">
                    <span class="text-lg font-semibold text-gray-800">{{ $work->user->name }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 画像とプロフィールの下に余白を開ける --}}
    <div class="mt-20 px-6 max-w-3xl mx-auto">
        {{-- タイトル --}}
        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $work->title }}</h1>

        {{-- 金額 --}}
        <p class="text-lg font-semibold text-green-600 mb-2">
            {{ number_format($work->price) != 0 ? number_format($work->price) . '円' : '相談の上決定' }}
        </p>

        {{-- カテゴリ --}}
        <p class="text-sm text-gray-500 mb-1">
            カテゴリ: {{ $work->category->name ?? '未分類' }}
        </p>

        {{-- 締切と納品 --}}
        <p class="text-sm text-gray-500 mb-1">
            締切: {{ $work->deadline ?? '未設定' }} / 納品: {{ $work->execution_date ?? '未設定' }}
        </p>

        {{-- 概要 --}}
        <div class="mt-4 text-gray-800 leading-relaxed whitespace-pre-line">
            {{ $work->description }}
        </div>
    </div>
    @if ($userApplications->isNotEmpty())
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            ※すでにこの仕事に見積もりを出しています（{{ $userApplications->count() }}件）
        </div>
    @endif
    <div class="mt-8 max-w-2xl mx-auto">

        @if(auth()->check())
        <form action="{{ route('works.apply', $work) }}" method="POST">
            @csrf

            {{-- 保有ポイント --}}
            <div class="text-sm text-right text-gray-600 mb-2">
                    現在の所持ポイント：<span class="font-semibold">{{ number_format(auth()->user()->points) }}</span> pt
            </div>

            {{-- 提示金額 --}}
            <label for="offer_price" class="block mt-4 mb-1 font-semibold">見積金額（任意）</label>
            <input type="number" name="offer_price"
                value="{{ old('offer_price') }}"
                class="w-full border rounded-md p-2"
                placeholder="例：3000">

            @error('offer_price')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror
            <input type="hidden" name="application_points"
                value="{{ old('application_points') }}"
                class="w-full border rounded-md p-2"
                placeholder="例：1000">

            @error('application_points')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            {{-- メッセージ --}}
            <label for="message" class="block mb-1 font-semibold">見積提案メッセージ（任意）</label>
            <textarea name="message" rows="4" maxlength="1024"
                    class="w-full border rounded-md p-2"
                    placeholder="申込にあたってのメッセージを入力">{{ old('message') }}</textarea>

            @error('message')
                <p class="text-sm text-red-500">{{ $message }}</p>
            @enderror

            {{-- ボタン --}}
            <button type="submit"
                    class="mt-4 w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                この仕事に見積を出す（1000pt消費）
            </button>
            @else
            <div class="text-red-600 text-sm mb-4">
                <p>この仕事に見積を出すにはログインが必要です。</p>
                <p>ログイン後、再度このページにアクセスしてください。</p>
            </div>
            <a href="{{ route('login') }}"
               class="block mt-6 w-full text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700">
                ログインする
            </a>    
            @endif
            {{-- フィードバック --}}
            @if (session('success'))
                <p class="mt-4 text-green-600">{{ session('success') }}</p>
            @endif
            @if (session('error'))
                <p class="mt-4 text-red-600">{{ session('error') }}</p>
            @endif
        </form>
    </div>   {{-- ボタン表示（ログインユーザーが申込済みでない想定） --}}
    @if ($latestApplications->isNotEmpty())
        <livewire:work-applications-list :work="$work" />
    @endif



    @if ($userApplications->isNotEmpty())
        <div class="mt-6">
            <h3 class="text-md font-semibold mb-2 text-gray-700">あなたの見積履歴</h3>
            <ul class="space-y-3 text-sm text-gray-800">
                @foreach ($userApplications as $app)
                    <li class="border rounded p-3">
                        <div class="text-xs text-gray-500 mb-1">
                            応募日時：{{ $app->created_at->format('Y年m月d日 H:i') }}　
                            ステータス：{{ $app->status }}
                            @if ($app->offer_price)
                                ／ 提示金額：{{ number_format($app->offer_price) }}円
                            @endif
                        </div>
                        <div class="whitespace-pre-line overflow-hidden text-ellipsis text-sm leading-relaxed"
                            style="-webkit-line-clamp: 3; display: -webkit-box; -webkit-box-orient: vertical;">
                            {{ $app->message }}
                        </div>

                    </li>
                @endforeach
            </ul>
            <div class="mt-4">
                {{ $userApplications->links() }}
            </div>
        </div>


    @endif
</x-app-layout>
