<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            ポイント購入
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto p-6 bg-white rounded shadow mt-8">
        <form method="POST" action="{{ route('points.checkout') }}">
            @csrf

            <label for="amount" class="block font-semibold mb-1">購入ポイント数（円換算）</label>
            <input type="number" name="amount" id="amount" min="100" max="100000"
                   class="w-full border rounded p-2 mb-4" placeholder="例: 1000" required>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                Stripeで購入
            </button>

            @if (session('error'))
                <p class="text-sm text-red-600 mt-2">{{ session('error') }}</p>
            @endif
        </form>
    </div>
</x-app-layout>
