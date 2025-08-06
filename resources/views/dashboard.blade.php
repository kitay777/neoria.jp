<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">ジョブマーケット</h2>
    <a href="{{ route('time-products.market') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        タイムワーカーマーケット →
    </a>
</div>
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach ($works as $work)
                    @php
                        $imageSrc = $work->image_path
                            ? asset('storage/' . $work->image_path)
                            : asset('/imgs/noimage.png');

                        $borderClass = "border-".$work->category->color_class ?? 'border-gray-300';
                        $bgClass = "bg-".$work->category->bg_color_class ?? 'bg-gray-100';
                        $barClass = "bg-".$work->category->color_class ?? 'bg-gray-300';
                    @endphp

                    <a href="{{ route('works.show', $work) }}" class="block w-full h-full">
                        <div class="rounded-xl border-2 {{ $borderClass }} shadow overflow-hidden flex flex-col hover:shadow-lg transition">
                            {{-- 画像 --}}
                            <img src="{{ $imageSrc }}" alt="{{ $work->title }}" class="w-full h-32 object-cover">

                            {{-- タイトル --}}
                            <div class="p-1 bg-white">
                                <h3 class="text-xl font-bold text-gray-900 tracking-tight line-clamp-2 min-h-[3.5rem]"
                                    style="font-family: 'YuGothic', 'メイリオ', sans-serif;">
                                    {{ $work->title }}
                                </h3>
                            </div>

                            {{-- 下部（背景つき） --}}
                            <div class="p-4 {{ $bgClass }} {{ $borderClass }}">
                                <p class="text-xl font-bold text-gray-900 tracking-tight mb-1" style="font-family: 'YuGothic', 'メイリオ', sans-serif;">
                                    {{ number_format($work->price) != 0 ? number_format($work->price) . '円' : '相談の上決定' }}
                                </p>
                                <p class="text-xs text-gray-600">{{ $work->category->name ?? '未分類' }}</p>
                                <p class="text-xs text-gray-600">申込締切日：{{ $work->deadline??'未設定' }}</p>
                                <p class="text-xs text-gray-600">実施日(納品日)：{{ $work->execution_date??'未設定' }}</p>
                                <p class="text-xs text-gray-700 truncate">{{ Str::limit($work->description, 40) }}</p>
                                <div class="flex items-center gap-2">
                                    <img src="{{ $work->user->profile_photo_path
                                        ? asset('storage/' . $work->user->profile_photo_path)
                                        : asset('/imgs/noimage.png') }}"
                                        alt="顔写真"
                                        class="w-8 h-8 rounded-full object-cover border">
                                    <span class="text-sm text-gray-800 font-medium">
                                        {{ $work->user->name }}
                                    </span>
                                </div>
                            </div>

                            {{-- 下部カラー帯 --}}
                            <div class="h-3 {{ $barClass ?? 'bg-gray-500' }} text-white flex items-center justify-center">・・・</div>
                        </div>
                    </a>

                @endforeach
            </div>

        </div>
    </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
            <a href="{{ route('riyou') }}" class="block text-gray-700 hover:text-blue-600">利用規約</a>
            <a href="{{ route('privacy') }}" class="block text-gray-700 hover:text-blue-600">プライバシーポリシー</a>
            <a href="{{ route('unei') }}" class="block text-gray-700 hover:text-blue-600">特定商取引法に基づく表記</a>
        
        </div>
</x-app-layout>
