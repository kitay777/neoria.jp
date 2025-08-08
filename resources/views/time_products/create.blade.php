<x-app-layout>
    <div class="max-w-2xl mx-auto py-10">
        <h2 class="text-xl font-bold mb-6">時間商品を登録する</h2>

        <form method="POST" action="{{ route('time-products.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- サービス名 --}}
            <div>
                <label class="block font-semibold">サービス名</label>
                <input type="text" name="title" class="w-full border rounded p-2" value="{{ old('title') }}" required>
            </div>

            {{-- 詳細説明 --}}
            <div>
                <label class="block font-semibold">詳細説明</label>
                <textarea name="description" class="w-full border rounded p-2" rows="5" required>{{ old('description') }}</textarea>
            </div>

            {{-- カテゴリ選択 --}}
            <div>
                <label class="block font-semibold">カテゴリ</label>
                <select name="category_id" class="w-full border rounded p-2">
                    <option value="">選択してください</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- 金額（ポイント） --}}
            <div>
                <label class="block font-semibold">金額（ポイント）</label>
                <input type="number" name="price" class="w-full border rounded p-2" value="{{ old('price') }}" required>
            </div>

            {{-- 所要時間 --}}
            <div>
                <label class="block font-semibold">時間（分）</label>
                <select name="duration" class="w-full border rounded p-2" required>
                    <option value="">選択してください</option>
                    @foreach ([0=>'1回',15=>'15分',30=>'30分',60=>'1時間',120=>'2時間',180=>'3時間',1440=>'1日',10080=>'1週間',43200=>'1ヶ月'] as $v=>$label)
                        <option value="{{ $v }}" @selected(old('duration') == $v)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- 取引形式 --}}
            <div>
                @php
                    $tradeTypes = [
                        'in_person' => '対面',
                        'online'    => 'オンライン',
                        'phone'     => '電話',
                        'message'   => 'メッセージ',
                    ];
                @endphp

                <div>
                    <label class="block font-semibold">取引形式</label>
                    <div class="flex flex-wrap gap-3 mt-2">
                        @foreach ($tradeTypes as $key => $label)
                            <label class="inline-flex items-center gap-2">
                                <input type="checkbox" name="trade_types[]" value="{{ $key }}" class="rounded"
                                    @checked(is_array(old('trade_types')) && in_array($key, old('trade_types'))) >
                                <span>{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('trade_types') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- 取引場所（都道府県） --}}
            <div>
                <label class="block font-semibold">取引場所（都道府県）</label>
                @php
                    $prefs = [
                        '北海道','青森県','岩手県','宮城県','秋田県','山形県','福島県',
                        '茨城県','栃木県','群馬県','埼玉県','千葉県','東京都','神奈川県',
                        '新潟県','富山県','石川県','福井県','山梨県','長野県',
                        '岐阜県','静岡県','愛知県','三重県',
                        '滋賀県','京都府','大阪府','兵庫県','奈良県','和歌山県',
                        '鳥取県','島根県','岡山県','広島県','山口県',
                        '徳島県','香川県','愛媛県','高知県',
                        '福岡県','佐賀県','長崎県','熊本県','大分県','宮崎県','鹿児島県','沖縄県',
                    ];
                @endphp
                <div>
                    <label class="block font-semibold">取引場所（都道府県）</label>
                    <select name="prefecture" class="w-full border rounded p-2">
                        <option value="">（未選択）</option>
                        @foreach ($prefs as $pref)
                        <option value="{{ $pref }}" @selected(old('prefecture') === $pref)>{{ $pref }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        ※オンライン／メッセージのみなら未選択でもOK。対面の場合は目安として選択してください。
                    </p>
                </div>
            </div>

            {{-- 画像アップロード --}}
            <div>
                <label class="block font-semibold">画像</label>
                <input type="file" name="image" accept="image/*" class="w-full border rounded p-2">
            </div>

            {{-- 登録ボタン --}}
            <div>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">登録する</button>
            </div>
        </form>
    </div>
</x-app-layout>
