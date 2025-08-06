<x-app-layout>
    <div class="max-w-4xl mx-auto py-10">
        <h2 class="text-xl font-bold mb-4">自分の出品一覧</h2>

        @if (session('success'))
            <div class="text-green-600 mb-4">{{ session('success') }}</div>
        @endif

        <a href="{{ route('time-products.create') }}" class="inline-block mb-4 bg-blue-600 text-white px-4 py-2 rounded">新規出品</a>

        <table class="w-full table-auto border">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">タイトル</th>
                    <th class="border px-4 py-2">価格</th>
                    <th class="border px-4 py-2">所要時間</th>
                    <th class="border px-4 py-2">公開</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td class="border px-4 py-2">{{ $product->title }}</td>
                        <td class="border px-4 py-2">{{ $product->price }} pt</td>
                        <td class="border px-4 py-2">{{ $product->duration }} 分</td>
                        <td class="border px-4 py-2">{{ $product->is_active ? '公開中' : '非公開' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">出品はまだありません</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
