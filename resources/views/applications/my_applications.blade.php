<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">自分が応募した仕事一覧</h2>
    </x-slot>

    <div class="p-6 space-y-4">
        @forelse ($applications as $application)
            <div class="p-4 border rounded bg-white shadow space-y-3">
                <div class="flex items-start space-x-4">
                    {{-- 仕事画像 --}}
                    @if ($application->work->image_path)
                        <img src="{{ asset('storage/' . $application->work->image_path) }}"
                             alt="仕事画像"
                             class="w-20 h-20 object-cover rounded">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded flex items-center justify-center text-gray-500">画像なし</div>
                    @endif

                    <div class="flex-1 space-y-1">
                        <h3 class="text-lg font-semibold">{{ $application->work->title }}</h3>
                        <p class="text-sm text-gray-600 line-clamp-2">
                            {{ Str::limit($application->work->description, 100) }}
                        </p>

                        {{-- 発注者の顔写真 --}}
                        <div class="flex items-center space-x-2 mt-1">
                            <img src="{{ $application->work->user->profile_photo_path
                                ? asset('storage/' . $application->work->user->profile_photo_path)
                                : asset('/imgs/noimage.png') }}"
                                class="w-8 h-8 rounded-full object-cover border"
                                alt="発注者アイコン">
                            <span class="text-sm text-gray-700">{{ $application->work->user->name }}</span>
                        </div>

                        {{-- 最新メッセージ（2行程度） --}}
                        @if ($application->messages->isNotEmpty())
                            <div class="text-sm text-gray-700 bg-gray-100 p-2 rounded mt-1">
                                {!! nl2br(e(Str::limit($application->messages->first()->message, 100))) !!}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- リンク --}}
                <div class="flex justify-end space-x-3 text-sm pt-2">
                    <a href="{{ route('works.show', $application->work_id) }}" class="text-blue-500 hover:underline">仕事詳細</a>
                    <a href="{{ route('chat.with', $application->id) }}" class="text-blue-600 font-bold hover:underline">
                        チャット →
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">応募した仕事はまだありません。</p>
        @endforelse

        <div>
            {{ $applications->links() }}
        </div>
    </div>
</x-app-layout>
