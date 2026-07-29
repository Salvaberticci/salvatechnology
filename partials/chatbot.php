<aside class="dash-chatbot" id="dashChatbot">
    <div class="chatbot-header">
        <div class="chatbot-header-left">
            <span class="chatbot-avatar">🧠</span>
            <div>
                <div class="chatbot-name">SALVA AI</div>
                <div class="chatbot-status"><span class="status-dot"></span>En línea</div>
            </div>
        </div>
        <button class="chatbot-toggle" id="chatbotToggle" title="Ocultar panel">▶</button>
    </div>
    <div class="chatbot-messages" id="chatbotMessages">
        <div class="chatbot-msg chatbot-msg-ai">
            <div class="msg-avatar">🧠</div>
            <div class="msg-content">
                <div class="msg-text">¡Hola! Soy SALVA AI, tu asistente de aprendizaje. Pregúntame sobre cursos, lecciones o conceptos de programación.</div>
                <div class="msg-time">Ahora</div>
            </div>
        </div>
    </div>
    <div class="chatbot-input-area">
        <input type="text" class="chatbot-input" id="chatbotInput" placeholder="Escribe tu mensaje..." autocomplete="off">
        <button class="chatbot-send" id="chatbotSend">▶</button>
    </div>
    <div class="chatbot-footer">
        <span class="text-stone-600 text-[9px] font-mono">SALVA AI · Powered by Groq</span>
    </div>
</aside>
<button class="chatbot-reopen" id="chatbotReopen" title="Abrir SALVA AI" style="display:none;">🧠</button>

<script>
(function() {
    var chatbot = document.getElementById('dashChatbot');
    var toggle = document.getElementById('chatbotToggle');
    var reopen = document.getElementById('chatbotReopen');
    var input = document.getElementById('chatbotInput');
    var sendBtn = document.getElementById('chatbotSend');
    var messagesEl = document.getElementById('chatbotMessages');
    if (!chatbot || !toggle || !reopen || !input || !sendBtn || !messagesEl) return;

    var chatHistory = [];
    var isSending = false;

    function setState(collapsed) {
        if (collapsed) {
            chatbot.classList.add('collapsed');
            toggle.textContent = '◀';
            toggle.title = 'Abrir SALVA AI';
            reopen.style.display = 'flex';
        } else {
            chatbot.classList.remove('collapsed');
            toggle.textContent = '▶';
            toggle.title = 'Ocultar panel';
            reopen.style.display = 'none';
        }
    }

    toggle.addEventListener('click', function() {
        setState(!chatbot.classList.contains('collapsed'));
    });

    reopen.addEventListener('click', function() {
        setState(false);
    });

    function scrollToBottom() {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    function addMessage(text, role) {
        var wrapper = document.createElement('div');
        wrapper.className = 'chatbot-msg chatbot-msg-' + role;

        var avatar = document.createElement('div');
        avatar.className = 'msg-avatar';
        avatar.textContent = role === 'ai' ? '🧠' : '👤';

        var content = document.createElement('div');
        content.className = 'msg-content';

        var msgText = document.createElement('div');
        msgText.className = 'msg-text';
        msgText.textContent = text;

        var msgTime = document.createElement('div');
        msgTime.className = 'msg-time';
        msgTime.textContent = 'Ahora';

        content.appendChild(msgText);
        content.appendChild(msgTime);
        wrapper.appendChild(avatar);
        wrapper.appendChild(content);
        messagesEl.appendChild(wrapper);
        scrollToBottom();
        return msgText;
    }

    function addTypingIndicator() {
        var wrapper = document.createElement('div');
        wrapper.className = 'chatbot-msg chatbot-msg-ai';
        wrapper.id = 'chatbot-typing';

        var avatar = document.createElement('div');
        avatar.className = 'msg-avatar';
        avatar.textContent = '🧠';

        var content = document.createElement('div');
        content.className = 'msg-content';

        var msgText = document.createElement('div');
        msgText.className = 'msg-text';
        msgText.innerHTML = '<span class="typing-dots"><span>.</span><span>.</span><span>.</span></span>';

        content.appendChild(msgText);
        wrapper.appendChild(avatar);
        wrapper.appendChild(content);
        messagesEl.appendChild(wrapper);
        scrollToBottom();
    }

    function removeTypingIndicator() {
        var el = document.getElementById('chatbot-typing');
        if (el) el.remove();
    }

    function sendMessage() {
        var text = input.value.trim();
        if (!text || isSending) return;

        isSending = true;
        sendBtn.disabled = true;
        input.disabled = true;

        addMessage(text, 'user');
        chatHistory.push({role: 'user', content: text});

        input.value = '';
        addTypingIndicator();

        fetch('/salvatechnology/api/chatbot.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                message: text,
                history: chatHistory
            })
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            removeTypingIndicator();
            if (data.error) {
                addMessage('Error: ' + data.error, 'ai');
            } else {
                addMessage(data.reply, 'ai');
                chatHistory.push({role: 'assistant', content: data.reply});
            }
        })
        .catch(function(err) {
            removeTypingIndicator();
            addMessage('Error de conexión. Intenta de nuevo.', 'ai');
        })
        .finally(function() {
            isSending = false;
            sendBtn.disabled = false;
            input.disabled = false;
            input.focus();
        });
    }

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
})();
</script>
