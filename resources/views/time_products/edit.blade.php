<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h2 class="text-xl font-bold mb-6">商品を編集する</h2>

        <form method="POST" action="{{ route('time-products.update', $timeProduct) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- サービス名 --}}
            <div>
                <label class="block font-semibold">サービス名</label>
                <input type="text" name="title" class="w-full border rounded p-2"
                       value="{{ old('title', $timeProduct->title) }}" required>
            </div>

            {{-- 詳細説明 --}}
            <div>
                <label class="block font-semibold">詳細説明</label>
                <textarea name="description" class="w-full border rounded p-2" rows="5" required>{{ old('description', $timeProduct->description) }}</textarea>
            </div>

            {{-- カテゴリ --}}
            <div>
                <label class="block font-semibold">カテゴリ</label>
                <select name="category_id" class="w-full border rounded p-2">
                    <option value="">選択してください</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $timeProduct->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 金額（ポイント） --}}
            <div>
                <label class="block font-semibold">金額（ポイント）</label>
                <input type="number" name="price" class="w-full border rounded p-2"
                       value="{{ old('price', $timeProduct->price) }}" required>
            </div>

            {{-- 所要時間 --}}

            <div>
                <label class="block font-semibold">所要時間</label>
                <select name="duration" class="w-full border rounded p-2" required>
                    <option value="">選択してください</option>
                    @foreach ([0, 15, 30, 60, 120, 180, 1440, 10080, 43200] as $min)
                        <option value="{{ $min }}"
                        @if($min == 0)
                            {{ old('duration', $timeProduct->duration) == 0 ? 'selected' : '' }}>
                            1回
                        @elseif($min < 60)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            {{ $min }} 分
                        @elseif($min == 60)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            1時間
                        @elseif($min == 120)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            2時間
                        @elseif($min == 180)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            3時間
                        @elseif($min == 1440)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            1日
                        @elseif($min == 10080)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            1週間
                        @elseif($min == 43200)
                            {{ old('duration', $timeProduct->duration) == $min ? 'selected' : '' }}>
                            1ヶ月
                        @endif

                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 現在の画像表示 --}}
            @if ($timeProduct->image_path)
                <div>
                    <label class="block font-semibold">現在の画像</label>
                    <img src="{{ asset('storage/' . $timeProduct->image_path) }}" alt="現在の画像" class="w-20 h-20 object-cover border">
                </div>
            @endif

            {{-- 新しい画像のアップロード --}}
            <div>
                <label class="block font-semibold">画像を変更する（任意）</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded p-2">
            </div>

            {{-- エラーメッセージ --}}
            @error('image')
                <div class="text-red-600">{{ $message }}</div>
            @enderror

            {{-- 取引形式 --}}
            @php
                $tradeTypes = [
                    'in_person' => '対面',
                    'online'    => 'オンライン',
                    'phone'     => '電話',
                    'message'   => 'メッセージ',
                ];
                // old優先。なければモデルのJSONカラム（配列）を使用
                $selectedTradeTypes = old('trade_types', $timeProduct->trade_types ?? []);
            @endphp

            <div>
                <label class="block font-semibold">取引形式</label>
                <div class="flex flex-wrap gap-3 mt-2">
                    @foreach ($tradeTypes as $key => $label)
                        <label class="inline-flex items-center gap-2">
                            <input
                                type="checkbox"
                                name="trade_types[]"
                                value="{{ $key }}"
                                class="rounded"
                                @checked(is_array($selectedTradeTypes) && in_array($key, $selectedTradeTypes))
                            >
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('trade_types') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- 取引場所（都道府県） --}}
            @php
                $prefectures = [
                    '北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
                    '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
                    '新潟県','富山県','石川県','福井県','山梨県','長野県',
                    '岐阜県','静岡県','愛知県','三重県',
                    '滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県',
                    '鳥取県','島根県','岡山県','広島県','山口県',
                    '徳島県','香川県','愛媛県','高知県',
                    '福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県',
                    '沖縄県',
                ];
                $selectedPref = old('prefecture', $timeProduct->prefecture ?? '');
            @endphp

            <div>
                <label class="block font-semibold">取引場所（都道府県）</label>
                <select name="prefecture" class="w-full border rounded p-2">
                    <option value="">選択してください</option>
                    @foreach ($prefectures as $pref)
                        <option value="{{ $pref }}" @selected($selectedPref === $pref)>{{ $pref }}</option>
                    @endforeach
                </select>
                @error('prefecture') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>



            {{-- 公開状態 --}}
            <div>
                <label class="inline-flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" class="mr-2"
                        value="1" {{ old('is_active', $timeProduct->is_active) ? 'checked' : '' }}>
                    公開する
                </label>
            </div>

            {{-- 送信 --}}
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">更新する</button>
            </div>
        </form>
    </div>
</x-app-layout>
