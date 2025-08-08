<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">仕事詳細（管理）</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        <div class="bg-white p-4 rounded shadow">
            <h3 class="text-lg font-semibold">{{ $work->title }}</h3>
            <p class="text-sm text-gray-600">カテゴリ: {{ $work->category->name ?? '未分類' }}</p>
            <p class="text-gray-800 mt-2">{{ $work->description }}</p>
            <p class="text-sm text-gray-500 mt-1">締切: {{ $work->deadline }} / 実施日: {{ $work->execution_date }}</p>
        </div>

        <div class="bg-white p-4 rounded shadow mt-6">
            <h4 class="text-md font-bold mb-2">見積提出者一覧</h4>

            @forelse ($work->applications as $application)
                <div class="flex justify-between items-center py-2 border-b last:border-0">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $application->user->profile_photo_path
                            ? asset('storage/' . $application->user->profile_photo_path)
                            : asset('/imgs/noimage.png') }}"
                            class="w-10 h-10 rounded-full object-cover border"
                            alt="応募者画像">

                        <div>
                            <p class="font-semibold text-gray-800">{{ $application->user->name }}</p>
                            <p class="text-xs text-gray-500">申込日時: {{ $application->created_at->format('Y/m/d H:i') }}</p>
                            <p class="text-sm text-gray-700 mt-1 line-clamp-2">{{ $application->message }}</p>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('applications.show', $application->id) }}"
                        class="text-sm bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-700">
                            見積詳細
                        </a>

                        <a href="{{ route('chat.with', $application->id) }}"
                        class="text-sm bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                        チャット
                        </a>
                    </div>

                </div>
            @empty
                <p class="text-sm text-gray-500">まだ見積がありません。</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
