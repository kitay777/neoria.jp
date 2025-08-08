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
            {{-- 申請フォーム + ボトムシート確認 --}}
            {{-- 申請フォーム + ボトムシート確認 --}}
            @auth
<div
    x-data="{
        open: false,
        hasEnough: {{ $hasEnoughPoints ? 'true' : 'false' }},
        userPoints: {{ (int)$userPoints }},
        price: {{ (int)$timeProduct->price }},
        showConfirm(){ this.open = true },
        submit(){ this.$refs.applyForm.requestSubmit() }
    }"
    class="mt-6"
>
@auth
<p class="mx-6 mt-2 text-sm text-gray-600">
    現在の所持ポイント：<span class="font-semibold">{{ number_format(auth()->user()->points) }}</span> pt
</p>
@endauth

@if($purchasedCount > 0)
    <div class="mx-6 mt-4 mb-2 rounded-lg border border-yellow-300 bg-yellow-50 text-yellow-900 p-3 flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-0.5 shrink-0" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-.75-4.5a.75.75 0 011.5 0v1.25a.75.75 0 01-1.5 0V13.5zm0-7.75a.75.75 0 011.5 0V11a.75.75 0 01-1.5 0V5.75z" clip-rule="evenodd" />
        </svg>
        <div>
            <div class="font-semibold">過去に購入済みのチケットです</div>
            <div class="text-sm">
                購入回数：<span class="font-semibold">{{ $purchasedCount }}</span>
                @if($lastPurchasedAt)
                    ／ 最終購入：<span class="font-mono">{{ $lastPurchasedAt->format('Y-m-d H:i') }}</span>
                @endif
            </div>
        </div>
    </div>
@endif
    <form x-ref="applyForm" method="POST" action="{{ route('time-products.apply', $timeProduct) }}">
        @csrf
        <textarea name="message" class="w-full border rounded p-2" rows="3" placeholder="出品者へのメッセージ（任意）"></textarea>

        {{-- いつもモーダルを出す（既申請の有無はここでは関係なし） --}}
        <button type="button"
                @click="showConfirm()"
                class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700">
            マッチング申請する
        </button>
    </form>

    {{-- オーバーレイ --}}
    <div x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-[90] bg-black/40"
         @click="open=false"
         @keydown.escape.window="open=false"
         style="display:none;"></div>

    {{-- ボトムシート --}}
    <div x-show="open"
         x-transition:enter="transform transition ease-out duration-200"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         x-transition:leave="transform transition ease-in duration-150"
         x-transition:leave-start="translate-y-0"
         x-transition:leave-end="translate-y-full"
         class="fixed z-[100] left-1/2 -translate-x-1/2 bottom-14 w-4/5 max-w-xl bg-white rounded-t-2xl shadow-2xl p-5"
         style="display:none; max-height: calc(100vh - 56px - 16px); overflow-y: auto;"
    >
        <div class="w-14 h-1.5 bg-gray-300 rounded-full mx-auto mb-4"></div>

        {{-- 上部ステータス表示（任意で既申請情報も表示） --}}
        <div class="text-sm text-gray-600 mb-3">
            必要ポイント：<span class="font-semibold" x-text="price.toLocaleString()"></span> pt ／
            所持ポイント：<span class="font-semibold" x-text="userPoints.toLocaleString()"></span> pt
            @if($purchasedCount > 0)
            <div class="text-sm">
                購入回数：<span class="font-semibold">{{ $purchasedCount }}</span>
                @if($lastPurchasedAt)
                    ／ 最終購入：<span class="font-mono">{{ $lastPurchasedAt->format('Y-m-d H:i') }}</span>
                @endif
            </div>
            @endif
        </div>

        {{-- 分岐: 不足 → 購入案内 ／ 足りる → 確認 --}}
        <template x-if="!hasEnough">
            <div>
                <h3 class="text-lg font-bold mb-2">ポイントが不足しています</h3>
                @if($purchasedCount > 0)
                    <p class="mb-2 inline-flex items-center gap-2 text-yellow-800 bg-yellow-50 border border-yellow-200 px-2 py-1 rounded">
                        <span class="text-xs font-semibold">再購入</span>
                        <span class="text-sm">過去に購入済みのチケットです。再購入にはポイントが必要です。</span>
                    </p>
                @endif
                <p class="text-sm text-gray-700 mb-6">
                    このチケットを購入するにはポイントが足りません。<br>
                    ポイントを購入してから再度お試しください。
                </p>
                <div class="flex gap-3">
                    <button type="button"
                            @click="open=false"
                            class="flex-1 border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50">
                        閉じる
                    </button>
                    <a href="{{ route('points.buy') }}"
                    class="flex-1 text-center bg-green-600 text-white px-4 py-2.5 rounded-lg hover:bg-green-700">
                        ポイントを購入する
                    </a>
                </div>
            </div>
        </template>

        {{-- 足りてる側 --}}
        <template x-if="hasEnough">
            <div>
                <h3 class="text-lg font-bold mb-2">購入の確認</h3>
                @if($purchasedCount > 0)
                    <p class="mb-2 inline-flex items-center gap-2 text-yellow-800 bg-yellow-50 border border-yellow-200 px-2 py-1 rounded">
                        <span class="text-xs font-semibold">再購入</span>
                        <span class="text-sm">過去に購入済みです。再度 {{ number_format($timeProduct->price) }} pt 消費します。</span>
                    </p>
                @endif
                <p class="text-sm text-gray-700 mb-6">
                    このチケットを <span class="font-semibold" x-text="price.toLocaleString()"></span> pt で購入します。よろしいですか？
                </p>
                <div class="flex gap-3">
                    <button type="button"
                            @click="open=false"
                            class="flex-1 border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-50">
                        キャンセル
                    </button>
                    <button type="button"
                            @click="submit()"
                            class="flex-1 bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700">
                        購入して申請する
                    </button>
                </div>
            </div>
        </template>
    </div>
</div>
@else
    <a href="{{ route('login') }}"
       class="block mt-6 w-full text-center bg-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:bg-blue-700">
       ログインして申請する
    </a>
@endauth




        </div>
    </div>
</x-app-layout>
