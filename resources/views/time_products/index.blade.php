<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h2 class="text-xl font-bold mb-4">自分の出品一覧</h2>

        @if (session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('time-products.create') }}" class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded">新規出品</a>

        <table class="w-full table-auto border text-sm">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="border px-4 py-2">タイトル</th>
                    <th class="border px-4 py-2">価格</th>
                    <th class="border px-4 py-2">時間</th>
                    <th class="border px-4 py-2">公開</th>
                    <th class="border px-4 py-2">操作</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="border px-4 py-2">{{ $product->title }}</td>
                        <td class="border px-4 py-2">{{ $product->price }} pt</td>
                        @if($product->duration == 0)
                            <td class="border px-4 py-2">1回</td>
                        @elseif($product->duration < 60)
                            <td class="border px-4 py-2">{{ $product->duration }} 分</td>
                        @elseif($product->duration == 60)
                            <td class="border px-4 py-2">1時間</td>
                        @elseif($product->duration == 120)
                            <td class="border px-4 py-2">2時間</td>
                        @elseif($product->duration == 180)
                            <td class="border px-4 py-2">3時間</td>
                        @elseif($product->duration == 1440)
                            <td class="border px-4 py-2">1日</td>
                        @elseif($product->duration == 10080)
                            <td class="border px-4 py-2">1週間</td>
                        @elseif($product->duration == 43200)
                            <td class="border px-4 py-2">1ヶ月</td>
                        @endif
                        <td class="border px-4 py-2">
                            {{ $product->is_active ? '公開中' : '非公開' }}
                        </td>
                        <td class="border px-4 py-2 space-x-2">
                            {{-- 編集ボタン --}}
                            <a href="{{ route('time-products.edit', $product) }}"
                               class="text-blue-600 hover:underline">編集</a>

                            {{-- 削除ボタン --}}
                            <form method="POST" action="{{ route('time-products.destroy', $product) }}"
                                  style="display: inline;" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">削除</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">出品はまだありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
