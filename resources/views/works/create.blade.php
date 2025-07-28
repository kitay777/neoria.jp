<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">新しい仕事を登録する</h2>
    </x-slot>

    <div class="p-6">
        <form method="POST" action="{{ route('works.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block">カテゴリー</label>
                <select name="category_id" class="w-full border p-2">
                    <option value="">-- 選択してください --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            @if (isset($work) && $work->image_path)
                <img src="{{ asset('storage/' . $work->image_path) }}"
                    alt="仕事画像"
                    class="w-64 h-64 object-cover rounded">
            @endif

            <div class="mb-4">
                <label class="block">画像（1024×1024）</label>
                <input type="file" name="image" accept="image/*" class="w-full border p-2">
                @error('image')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="block">タイトル</label>
                <input type="text" name="title" class="w-full border p-2" value="{{ old('title') }}">
                @error('title')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>



            <div class="mb-4">
                <label class="block">詳細</label>
                <textarea name="description" class="w-full border p-2">{{ old('description') }}</textarea>
                @error('description')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="block">勤務地（任意）</label>
                <input type="text" name="location" class="w-full border p-2" value="{{ old('location') }}">
            </div>
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_overseas_allowed" class="form-checkbox" {{ old('is_overseas_allowed') ? 'checked' : '' }}>
                    <span class="ml-2">海外業者の応募を許可する</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="is_verified_by_client" class="form-checkbox" {{ old('is_verified_by_client') ? 'checked' : '' }}>
                    <span class="ml-2">内容を確認しました</span>
                </label>
            </div>

            <div class="mb-4">
                <label class="block">報酬（円）</label>
                <input type="number" name="price" class="w-full border p-2" value="{{ old('price') }}">
                @error('price')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>

            <div class="mb-4">
                <label class="block">応募締切日</label>
                <input type="date" name="deadline" class="w-full border p-2" value="{{ old('deadline') }}">
                @error('deadline')<div class="text-red-500">{{ $message }}</div>@enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">実施日(納品日)</label>
                <input type="date" name="execution_date" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"
                    value="{{ old('execution_date') }}">
            </div>


            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">仕事を登録</button>
        </form>
    </div>
</x-app-layout>
