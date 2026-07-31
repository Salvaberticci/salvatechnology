const input = document.getElementById('ai-chat-input');
const panel = document.getElementById('ai-chat-panel');
const messagesBox = document.getElementById('ai-chat-messages');
const typing = document.getElementById('ai-chat-typing');
const closeBtn = document.getElementById('ai-chat-close');
const API_URL = window.SALVA_CHAT_API || 'api/chatbot.php';

const history = [];

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatReply(text) {
    let html = escapeHtml(text);
    html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
    html = html.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/\n/g, '<br>');
    return html;
}

function addMessage(role, text) {
    const msg = document.createElement('div');
    msg.className = 'chat-msg ' + role;
    msg.innerHTML = role === 'bot'
        ? '<div class="chat-bubble bot">' + formatReply(text) + '</div>'
        : '<div class="chat-bubble user">' + escapeHtml(text) + '</div>';
    messagesBox.appendChild(msg);
    messagesBox.scrollTop = messagesBox.scrollHeight;
}

function showTyping(on) {
    typing.style.display = on ? 'block' : 'none';
}

function openPanel() {
    panel.classList.add('open');
    input.focus();
}

function closePanel() {
    panel.classList.remove('open');
    input.blur();
}

input.addEventListener('keydown', async (e) => {
    if (e.key === 'Escape') {
        closePanel();
        return;
    }
    if (e.key !== 'Enter') return;

    const text = input.value.trim();
    if (!text) return;

    input.value = '';
    openPanel();
    addMessage('user', text);
    history.push({ role: 'user', content: text });

    showTyping(true);
    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ message: text, history: history.slice(-20) }),
        });
        const data = await res.json();
        if (data.reply) {
            addMessage('bot', data.reply);
            history.push({ role: 'assistant', content: data.reply });
        } else {
            addMessage('bot', '⚠️ ' + (data.error || 'No pude obtener una respuesta. Intenta de nuevo.'));
        }
    } catch (err) {
        addMessage('bot', '⚠️ Error de conexión con el asistente. Verifica tu internet e intenta de nuevo.');
    } finally {
        showTyping(false);
        input.focus();
    }
});

closeBtn.addEventListener('click', closePanel);

panel.classList.add('open');
setTimeout(() => {
    addMessage('bot', '¡Hola! 👋 Soy **SALVA AI**, tu asistente de la academia. Puedes preguntarme sobre los cursos, la metodología **ADD**, los planes de suscripción o sobre Salvatore. ¿En qué te ayudo?');
}, 800);
