<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4">
        <a href="{{ route('time-products.market') }}"
        class="inline-block mb-6 bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300">
            ← マーケットに戻る
        </a>

        <div class="bg-white shadow rounded-lg overflow-hidden border">
            <img src="{{ $timeProduct->image_path ? asset('storage/' . $timeProduct->image_path) : asset('/imgs/noimage.png') }}"
                 alt="{{ $timeProduct->title }}"
                 class="w-full h-64 object-cover">
            <div class="flex items-center gap-3 mt-6">
                <img src="{{ $timeProduct->user->profile_photo_path
                    ? asset('storage/' . $timeProduct->user->profile_photo_path)
                    : asset('/imgs/noimage.png') }}"
                    class="w-10 h-10 rounded-full object-cover border"
                    alt="出品者">
                <span class="font-medium">{{ $timeProduct->user->name }}</span>
            </div>
            <div class="p-6 space-y-4">
                <h2 class="text-2xl font-bold">{{ $timeProduct->title }}</h2>

                <p class="text-gray-600">カテゴリ：{{ $timeProduct->category->name ?? '未分類' }}</p>
                <p class="text-gray-600">時間：
                    @if($timeProduct->duration == 0)1回
                    @elseif($timeProduct->duration < 60){{ $timeProduct->duration }}分
                    @elseif($timeProduct->duration == 60)1時間
                    @elseif($timeProduct->duration == 120)2時間
                    @elseif($timeProduct->duration == 180)3時間
                    @elseif($timeProduct->duration == 1440)1日
                    @elseif($timeProduct->duration == 10080)1週間
                    @elseif($timeProduct->duration == 43200)1ヶ月
                    @endif
                </p>
                <p class="text-gray-600">価格：{{ number_format($timeProduct->price) }} pt</p>

                <div class="text-gray-800 leading-relaxed">
                    {!! nl2br(e($timeProduct->description)) !!}
                </div>
            </div>
            {{-- フラッシュ --}}
            @if (session('success'))
                <div class="mb-4 text-green-700 bg-green-100 px-3 py-2 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @error('points')
                <div class="mb-4 text-red-700 bg-red-100 px-3 py-2 rounded">
                    {{ $message }}
                </div>
            @enderror

            {{-- マッチング申請フォーム（要ログイン） --}}
            @auth
            <form action="{{ route('time-products.apply', $timeProduct) }}" method="POST" class="mt-6 space-y-3">
                @csrf
                <textarea name="message" class="w-full border rounded p-2" rows="3" placeholder="出品者へのメッセージ（任意）"></textarea>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    {{ number_format($timeProduct->price) }} pt でマッチング申請する
                </button>
            </form>
            @else
            <div class="mt-6">
                <a href="{{ route('login') }}" class="bg-blue-600 text-white px-4 py-2 rounded">ログインして申請する</a>
            </div>
            @endauth

        </div>
    </div>
</x-app-layout>
