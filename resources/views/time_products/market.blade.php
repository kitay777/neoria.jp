<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-2xl font-bold mb-6 text-center">時間商品マーケット</h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                @foreach ($products as $product)
                    @php
                        $imageSrc = $product->image_path
                            ? asset('storage/' . $product->image_path)
                            : asset('/imgs/noimage.png');

                        $category     = $product->category;
                        $categoryName = $category->name ?? '未分類';

                        $borderClass = 'border-' . ($category->color_class ?? 'gray-300');
                        $bgClass     = 'bg-' . ($category->bg_color_class ?? 'gray-100');
                        $barClass    = 'bg-' . ($category->color_class ?? 'gray-300');
                    @endphp

                    <div class="block w-full h-full">
                        <div class="rounded-xl border-2 {{ $borderClass }} shadow overflow-hidden flex flex-col hover:shadow-lg transition">
                            
                            {{-- 画像 --}}
                            <img src="{{ $imageSrc }}" alt="{{ $product->title }}" class="w-full h-32 object-cover">

                            {{-- タイトル --}}
                            <div class="p-1 bg-white">
                                <h3 class="text-xl font-bold text-gray-900 tracking-tight line-clamp-2 min-h-[3.5rem]"
                                    style="font-family: 'YuGothic', 'メイリオ', sans-serif;">
                                    {{ $product->title }}
                                </h3>
                            </div>

                            {{-- 下部情報 --}}
                            <div class="p-4 {{ $bgClass }} {{ $borderClass }}">
                                <p class="text-xl font-bold text-gray-900 tracking-tight mb-1" style="font-family: 'YuGothic', 'メイリオ', sans-serif;">
                                    {{ number_format($product->price) }} pt
                                </p>
                                <p class="text-xs text-gray-600">カテゴリ：{{ $categoryName }}</p>
                                <p class="text-xs text-gray-600">所要時間：{{ $product->duration }} 分</p>
                                <p class="text-xs text-gray-700 truncate">{{ Str::limit($product->description, 40) }}</p>
                                <div class="flex items-center gap-2 mt-2">
                                    <img src="{{ $product->user->profile_photo_path
                                        ? asset('storage/' . $product->user->profile_photo_path)
                                        : asset('/imgs/noimage.png') }}"
                                        alt="顔写真"
                                        class="w-8 h-8 rounded-full object-cover border">
                                    <span class="text-sm text-gray-800 font-medium">
                                        {{ $product->user->name }}
                                    </span>
                                </div>
                            </div>

                            {{-- 下部帯 --}}
                            <div class="h-3 {{ $barClass }} text-white flex items-center justify-center">・・・</div>
                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
