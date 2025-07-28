<x-app-layout>
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white text-center shadow rounded">
        <h2 class="text-2xl font-bold mb-4">ポイント購入完了</h2>
        <p class="text-green-600 text-lg">+{{ $points }} ポイントを加算しました！</p>
        <a href="{{ route('points.history') }}"
           class="inline-block mt-4 text-blue-600 hover:underline">ポイント履歴を見る</a>
    </div>
</x-app-layout>
