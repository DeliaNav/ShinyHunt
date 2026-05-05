// public/js/chat.js

document.addEventListener('DOMContentLoaded', () => {

    const messagesEl  = document.getElementById('chat-messages');
    const inputEl     = document.getElementById('chat-input');
    const sendBtn     = document.getElementById('chat-send');
    const receiverId  = document.getElementById('receiver-id')?.value;

    if (!messagesEl || !inputEl || !sendBtn || !receiverId) return;

    // Scroll al último mensaje al cargar
    scrollToBottom();

    // Enviar con Enter (Shift+Enter = salto de línea)
    inputEl.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    sendBtn.addEventListener('click', sendMessage);

    // Auto-resize del textarea
    inputEl.addEventListener('input', () => {
        inputEl.style.height = 'auto';
        inputEl.style.height = Math.min(inputEl.scrollHeight, 120) + 'px';
    });

    async function sendMessage() {
        const content = inputEl.value.trim();
        if (!content) return;

        sendBtn.disabled = true;

        try {
            const res  = await fetch('/TFG/Codigo/mensajes/send', {
                method:  'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body:    'receiver_id=' + encodeURIComponent(receiverId)
                       + '&content='    + encodeURIComponent(content),
            });

            const data = await res.json();

            if (data.success) {
                inputEl.value = '';
                inputEl.style.height = 'auto';
                appendBubble(data.message, data.sent_at);
                scrollToBottom();
            }
        } catch (err) {
            console.error('Error al enviar mensaje:', err);
        } finally {
            sendBtn.disabled = false;
            inputEl.focus();
        }
    }

    function appendBubble(content, time) {
        const wrap = document.createElement('div');
        wrap.className = 'chat-bubble-wrap chat-bubble-wrap--own';

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble chat-bubble--own';

        const text = document.createElement('p');
        text.className = 'chat-bubble-text';
        // Respeta saltos de línea
        text.innerHTML = content.replace(/\n/g, '<br>');

        const timeEl = document.createElement('span');
        timeEl.className = 'chat-bubble-time';
        timeEl.textContent = time;

        bubble.appendChild(text);
        bubble.appendChild(timeEl);
        wrap.appendChild(bubble);
        messagesEl.appendChild(wrap);
    }

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }
});