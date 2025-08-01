import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// 例：app.js の末尾など
window.Echo.channel('chat.' + window.applicationId)
    .listen('MessageSent', (e) => {
        const container = document.getElementById('chat-messages');
        if (!container) return;

        const isSelf = e.user.id === window.Laravel.userId; // Auth::user()->id を渡している場合

        const wrapper = document.createElement('div');
        wrapper.className = 'flex ' + (isSelf ? 'justify-end' : 'justify-start');

        const inner = document.createElement('div');
        inner.className = 'max-w-xs bg-gray-100 rounded px-3 py-2 text-sm text-gray-800';
        inner.innerHTML = `<strong>${e.user.name}:</strong><br>${e.message.replace(/\n/g, '<br>')}`;

        wrapper.appendChild(inner);
        container.appendChild(wrapper);

        // 下にスクロール
        const scrollArea = document.getElementById('chat-scroll-area');
        scrollArea.scrollTop = scrollArea.scrollHeight + 100;
    });


