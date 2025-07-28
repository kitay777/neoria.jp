<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            ポイント履歴
        </h2>
    </x-slot>

<div class="max-w-6xl mx-auto p-6">
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-2 text-left">日時</th>
                    <th class="px-4 py-2 text-left">区分</th>
                    <th class="px-4 py-2 text-left">内容</th>
                    <th class="px-4 py-2 text-right">増減</th>
                    <th class="px-4 py-2 text-right">残ポイント</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($logs as $log)
                    <tr>
                        <td class="px-4 py-2">{{ $log->created_at->format('Y/m/d H:i') }}</td>

                        {{-- 区分日本語 --}}
                        <td class="px-4 py-2">
                            @switch($log->type)
                                @case('apply') 応募 @break
                                @case('bonus') ボーナス @break
                                @case('admin') 管理者操作 @break
                                @case('refund') 返金 @break
                                @default その他
                            @endswitch
                        </td>

                        {{-- 内容（works.titleリンク） --}}
                        <td class="px-4 py-2">
                            @if ($log->application && $log->application->work)
                                <a href="{{ route('works.show', $log->application->work) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $log->application->work->title }}
                                </a>
                            @else
                                {{ $log->description ?? '（内容なし）' }}
                            @endif
                        </td>

                        {{-- 増減 --}}
                        <td class="px-4 py-2 text-right">
                            <span class="{{ $log->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $log->amount > 0 ? '+' : '' }}{{ $log->amount }}pt
                            </span>
                        </td>

                        {{-- 残ポイント --}}
                        <td class="px-4 py-2 text-right text-gray-700">
                            {{ $log->balance }} pt
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">履歴がありません。</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ページネーション --}}
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>

</x-app-layout>
