<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h2 class="text-xl font-bold mb-6">時間商品を登録する</h2>

        <form method="POST" action="{{ route('time-products.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- サービス名 --}}
            <div>
                <label class="block font-semibold">サービス名</label>
                <input type="text" name="title" class="w-full border rounded p-2" required>
            </div>

            {{-- 詳細説明 --}}
            <div>
                <label class="block font-semibold">詳細説明</label>
                <textarea name="description" class="w-full border rounded p-2" rows="5" required></textarea>
            </div>

            {{-- カテゴリ選択 --}}
            <div>
                <label class="block font-semibold">カテゴリ</label>
                <select name="category_id" class="w-full border rounded p-2">
                    <option value="">選択してください</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 金額（ポイント） --}}
            <div>
                <label class="block font-semibold">金額（ポイント）</label>
                <input type="number" name="price" class="w-full border rounded p-2" required>
            </div>

            {{-- 所要時間 --}}
            <div>
                <label class="block font-semibold">所要時間（分）</label>
                <select name="duration" class="w-full border rounded p-2" required>
                    <option value="15">15分</option>
                    <option value="30">30分</option>
                    <option value="60">60分</option>
                </select>
            </div>

            {{-- 画像アップロード --}}
            <div>
                <label class="block font-semibold">画像（任意）</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded p-2">
            </div>

            {{-- 登録ボタン --}}
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">登録する</button>
            </div>
        </form>
    </div>
</x-app-layout>
