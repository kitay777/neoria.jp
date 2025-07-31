<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold">チャット（{{ $application->user->name }} さん）</h2>
    </x-slot>

    <!-- チャットメッセージ一覧 -->
<div id="chat-scroll-area" class="p-6 pb-40 space-y-4 max-h-[calc(100vh-10rem)] overflow-y-auto">
    <div class="space-y-3">
        @foreach ($messages->reverse() as $msg)
            <div class="flex {{ $msg->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs bg-gray-100 rounded px-3 py-2 text-sm text-gray-800">
                    <strong>{{ $msg->user->name }}:</strong><br>
                    {!! nl2br(e($msg->message)) !!}
                </div>
            </div>
        @endforeach
    </div>
</div>

    <!-- 入力欄（画面下に固定） -->
    <form
        action="{{ route('chat.send', $application) }}"
        method="POST"
        class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 flex gap-2 z-50 pb-16"
    >
        @csrf
        <textarea
            id="chat-message"
            name="message"
            rows="3"
            class="flex-1 border rounded px-3 py-2 resize-none focus:outline-none focus:ring focus:ring-blue-200"
            placeholder="メッセージを入力してください"
            required
        ></textarea>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            送信
        </button>
    </form>

    <!-- ✅ スクリプトはここに置く -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const textarea = document.getElementById('chat-message');
            textarea.addEventListener('input', function () {
                textarea.scrollTop = textarea.scrollHeight + 120;
            });
        });
    </script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chatArea = document.getElementById('chat-scroll-area');
        const textarea = document.getElementById('chat-message');

        // 表示時に最下部にスクロール
        if (chatArea) {
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        // 入力時にスクロール位置を最下部に
        const scrollToBottom = () => {
            setTimeout(() => {
                chatArea.scrollTop = chatArea.scrollHeight;
            }, 0);
        };

        ['input', 'focus', 'keyup'].forEach(event => {
            textarea.addEventListener(event, scrollToBottom);
        });
    });
</script>

</x-app-layout>
