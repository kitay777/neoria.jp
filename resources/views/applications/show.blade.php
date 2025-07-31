<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">応募詳細</h2>
    </x-slot>

    <div class="p-6 space-y-4">
        <div class="bg-white p-4 rounded shadow">
            <p class="text-sm text-gray-500">応募者: {{ $application->user->name }}</p>
            <p class="text-sm text-gray-500">応募日時: {{ $application->created_at->format('Y/m/d H:i') }}</p>

            <h3 class="font-semibold mt-4 mb-1">応募メッセージ</h3>
            <p class="text-gray-800 whitespace-pre-line">{{ $application->message }}</p>

            {{-- 必要ならここに追加情報表示 --}}
        </div>

        <div>
            <a href="{{ route('works.manage.show', $application->work_id) }}" class="text-sm text-blue-600 hover:underline">
                ← 戻る
            </a>
        </div>
    </div>
</x-app-layout>
