<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">エキスパートマーケット</h2>
    <a href="{{ route('dashboard') }}"
       class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
        ジョブマーケット →
    </a>
</div>
<form method="POST" action="{{ route('session.setCategoryFilter') }}" class="mb-6" x-data="{ open: '' }">
    @csrf
    @php
        $selectedIds = session('category_filter_ids', []);
    @endphp

    <div class="flex flex-wrap gap-2">
@foreach ($categories as $parent)
    @php
        $hasSelectedChild = $parent->children->pluck('id')->intersect($selectedIds)->isNotEmpty();
        $parentId = (string) $parent->id;
    @endphp

    <button
        type="button"
        @click="open = open === '{{ $parentId }}' ? '' : '{{ $parentId }}'"
        class="px-3 py-1 rounded-full text-sm transition"
        x-bind:class="open === '{{ $parentId }}'
            ? 'bg-blue-600 text-white'
            : '{{ $hasSelectedChild ? 'bg-blue-200 text-blue-800' : 'bg-gray-300 text-gray-800 hover:bg-gray-400' }}'">
        {{ $parent->name }}
    </button>
@endforeach



    </div>

    {{-- ✅ ここが重要：比較を文字列に揃える --}}
    <template x-if="open !== ''">
        <div class="mt-4 flex flex-wrap gap-2 pl-4">
            @foreach ($categories as $parent)
                <template x-if="open === '{{ $parent->id }}'">
                    <div>
                        @foreach ($parent->children as $child)
                            @php
                                $isSelected = in_array($child->id, $selectedIds);
                            @endphp
                            <button type="submit" name="category_id" value="{{ $child->id }}"
                                class="px-3 py-1 rounded-full text-sm border transition
                                    {{ $isSelected ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800 hover:bg-gray-300' }}">
                                {{ $child->name }}
                            </button>
                        @endforeach
                    </div>
                </template>
            @endforeach
        </div>
    </template>


    @if (!empty($selectedIds))
        <div class="mt-2">
            <button type="submit" name="clear" value="1"
                class="px-3 py-1 rounded-full text-sm bg-red-100 text-red-700 hover:bg-red-200 border">
                フィルター解除
            </button>
        </div>
    @endif
</form>


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
                        <a href="{{ route('time-products.show', $product) }}" class="block w-full h-full">
<div class="rounded-xl border-2 {{ $borderClass }} shadow overflow-hidden flex flex-col hover:shadow-lg transition h-full">
    
    {{-- 画像 --}}
    <img src="{{ $imageSrc }}" alt="{{ $product->title }}" class="w-full h-32 object-cover rounded-t-xl">

    {{-- タイトル --}}
    <div class="p-1 bg-white">
        <h3 class="text-xl font-bold text-gray-900 tracking-tight line-clamp-2 min-h-[3.5rem]">
            {{ $product->title }}
        </h3>
    </div>

    {{-- 情報エリア --}}
    <div class="p-4 {{ $bgClass }} {{ $borderClass }} flex-1">
        <p class="text-xl font-bold text-gray-900 tracking-tight mb-1">
            {{ number_format($product->price) }} pt
        </p>
        <p class="text-xs text-gray-600">カテゴリ：{{ $categoryName }}</p>
        <p class="text-xs text-gray-600">所要時間：{{ $product->duration }} 分</p>
        <p class="text-xs text-gray-700 truncate">{{ Str::limit($product->description, 40) }}</p>
    </div>

    {{-- ✅ ユーザー情報（下固定） --}}
    <div class="flex items-center gap-2 px-4 py-2 mt-auto {{ $bgClass }}">
        <img src="{{ $product->user->profile_photo_path
            ? asset('storage/' . $product->user->profile_photo_path)
            : asset('/imgs/noimage.png') }}"
            alt="顔写真"
            class="w-8 h-8 rounded-full object-cover border">
        <span class="text-sm text-gray-800 font-medium">
            {{ $product->user->name }}
        </span>
    </div>

    {{-- ○ ○ ○ バー --}}
    <div class="h-3 {{ $barClass ?? 'bg-gray-500' }} text-white flex items-center justify-center rounded-b-xl">
        ○ ○ ○
    </div>
</div>

                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
