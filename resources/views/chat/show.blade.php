<x-app-layout>
    <style>
        .my-4 {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        hr {
            height: 1px;
            background-color: #ccc;
        }
    </style>
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
        @php
            $lastDate = null;
        @endphp

        @foreach ($messages->reverse() as $msg)
            @php
                $currentDate = $msg->created_at->format('Y-m-d');
            @endphp

            @if ($currentDate !== $lastDate)
                <div class="flex justify-center items-center my-4">
                    <hr class="flex-grow border-t border-gray-300">
                    <span class="px-3 text-gray-500 text-sm whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($currentDate)->format('Y年m月d日') }}
                    </span>
                    <hr class="flex-grow border-t border-gray-300">
                </div>
                @php
                    $lastDate = $currentDate;
                @endphp
            @endif

            <div class="flex {{ $msg->user_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs bg-gray-100 rounded px-3 py-2 text-sm text-gray-800">
                    <strong>{{ $msg->user->name }}</strong>
                    <span class="text-xs text-gray-500 ml-2">{{ $msg->created_at->format('H:i') }}</span><br>
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
        .listen('App\\Events\\MessageSent', (e) => {
            console.log("新しいメッセージ受信:", e);
            if (e.user.id === window.Laravel.userId) return;

            const timeStr = e.created_at.slice(11, 16); // 'HH:mm'

            const wrapper = document.createElement('div');
            console.log("time:::", timeStr);
            wrapper.className = 'flex justify-start';

            const inner = document.createElement('div');
            inner.className = 'max-w-xs bg-gray-100 rounded px-3 py-2 text-sm text-gray-800';
            inner.innerHTML = `<strong>${e.user.name}</strong><span class="text-xs text-gray-500 ml-2">${timeStr}</span><br>${e.message.replace(/\n/g, '<br>')}`;

            wrapper.appendChild(inner);
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
            const now = new Date();
            const hour = now.getHours().toString().padStart(2, '0');
            const min = now.getMinutes().toString().padStart(2, '0');
            const timeStr = `${hour}:${min}`;

            const wrapper = document.createElement('div');
            wrapper.className = 'flex justify-end';

            const inner = document.createElement('div');
            inner.className = 'max-w-xs bg-gray-100 rounded px-3 py-2 text-sm text-gray-800';
            inner.innerHTML = `<strong>あなた</strong><span class="text-xs text-gray-500 ml-2">${timeStr}</span><br>${message.replace(/\n/g, '<br>')}`;

            messages.appendChild(wrapper);
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
