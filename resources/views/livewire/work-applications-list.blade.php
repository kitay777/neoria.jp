<div class="mt-8 max-w-4xl mx-auto">
    <h3 class="text-lg font-bold mb-4">申込者一覧（最新の申込のみ）</h3>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-1">
        @foreach ($latestApplications as $app)
            <div class="flex items-start gap-4 p-4 rounded-lg border shadow-sm"
                style="background-color: #E0F7F3;">
                
                {{-- プロフィール画像 --}}
                <img src="{{ $app->user->profile_photo_path
                    ? asset('storage/' . $app->user->profile_photo_path)
                    : asset('/imgs/noimage.png') }}"
                    alt="顔"
                    class="w-14 h-14 rounded-full object-cover border">

                <div class="flex-1">
                    <div class="flex justify-between items-center">
                        <span class="font-semibold text-gray-800">{{ $app->user->name }}</span>
                        @if ($app->offer_price)
                            <span class="text-sm text-green-700 font-bold">{{ number_format($app->offer_price) }}円</span>
                        @endif
                    </div>

                    <div class="text-sm text-gray-700 mt-1 whitespace-pre-line overflow-hidden"
                        style="-webkit-line-clamp: 5; display: -webkit-box; -webkit-box-orient: vertical;">
                        {{ $app->message }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $latestApplications->links() }}
    </div>
</div>
