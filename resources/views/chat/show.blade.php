<x-app-layout>
    <script>
        window.applicationId = {{ $application->id }};
        window.Laravel = {
            userId: {{ Auth::id() }},
        };
    </script>

    <x-slot name="header">
        <h2 class="text-xl font-bold">チャット（{{ $application->user->name }} さん）</h2>
    </x-slot>

    <!-- チャットメッセージ一覧 -->
    <div id="chat-scroll-area" class="p-6 pb-40 space-y-4 max-h-[calc(100vh-10rem)] overflow-y-auto">
        <div id="chat-messages" class="space-y-3">
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

    <!-- 入力欄 -->
    <form
        id="chat-form"
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
    


    <!-- スクロール補正＆リアルタイム受信スクリプト -->
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const chatArea = document.getElementById('chat-scroll-area');
    const textarea = document.getElementById('chat-message');
    const messages = document.getElementById('chat-messages');
    const form = document.getElementById('chat-form');

    // 初回スクロール
    chatArea.scrollTop = chatArea.scrollHeight;

    // 入力中もスクロール追従
    const scrollToBottom = () => {
        setTimeout(() => {
            chatArea.scrollTop = chatArea.scrollHeight;
        }, 0);
    };
    ['input', 'focus', 'keyup'].forEach(evt =>
        textarea.addEventListener(evt, scrollToBottom)
    );

    // Echo（Pusher）リアルタイム受信
    if (window.Echo && window.applicationId) {
        window.Echo.channel('chat.' + window.applicationId)
            .listen('MessageSent', (e) => {
                if (e.user.id === window.Laravel.userId) return; // 自分の送信は表示済み

                const wrapper = document.createElement('div');
                wrapper.className = 'flex justify-start';

                const inner = document.createElement('div');
                inner.className = 'max-w-xs bg-gray-100 rounded px-3 py-2 text-sm text-gray-800';
                inner.innerHTML = `<strong>${e.user.name}:</strong><br>${e.message.replace(/\n/g, '<br>')}`;

                //wrapper.appendChild(inner);
                messages.appendChild(wrapper);
                chatArea.scrollTop = chatArea.scrollHeight;
            });
    }

    // 送信処理
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const message = textarea.value.trim();
        if (!message) return;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message })
            });

            if (response.ok) {
                textarea.value = '';
                textarea.focus();
                scrollToBottom();
            } else {
                console.error('送信失敗', await response.text());
            }
        } catch (err) {
            console.error('送信エラー', err);
        }
    });
});

</script>

</x-app-layout>
