<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">仕事一覧</h2>
    </x-slot>

    <div class="p-6 space-y-6">
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        @foreach ($works as $work)
            <div class="border rounded p-4 flex space-x-4 items-start">
                @if ($work->image_path)
                    <img src="{{ asset('storage/' . $work->image_path) }}"
                         alt="画像"
                         class="w-32 h-32 object-cover rounded">
                @endif

                <div class="flex-1">
                    <h3 class="text-lg font-semibold">{{ $work->title }}</h3>
                    <p class="text-sm text-gray-600 mb-1">
                        カテゴリー: {{ $work->category->name ?? '未分類' }}
                    </p>
                    <p class="text-gray-700">{{ Str::limit($work->description, 100) }}</p>

                    <div class="mt-2 space-x-2">
                        <a href="{{ route('works.show', $work) }}"
                           class="text-blue-600 hover:underline">詳細</a>

                        <a href="{{ route('works.edit', $work) }}"
                           class="text-yellow-600 hover:underline">編集</a>

                        <form action="{{ route('works.destroy', $work) }}"
                              method="POST"
                              class="inline"
                              onsubmit="return confirm('削除してもよろしいですか？')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline">削除</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- ページネーション -->
        <div>
            {{ $works->links() }}
        </div>
    </div>
</x-app-layout>
