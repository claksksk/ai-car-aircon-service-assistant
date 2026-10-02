<?php
/*
|--------------------------------------------------------------------------
| CAR AIRCON ONLINE BOOKING SYSTEM - AI CHATBOT WIDGET (chatbot.php)
|--------------------------------------------------------------------------
*/
?>
<!-- Chatbot Floating Launcher Button -->
<div id="chat-launcher" onclick="toggleChatbot()" style="position: fixed; bottom: 25px; right: 25px; z-index: 9999; cursor: pointer;">
    <div class="bg-primary text-white rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; transition: all 0.3s ease;">
        <i class="fa-solid fa-comments fa-2x"></i>
    </div>
    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
        <span class="visually-hidden">New alert</span>
    </span>
</div>

<!-- Chatbot Box -->
<div id="chat-box" class="shadow-lg rounded-4 overflow-hidden d-none" style="position: fixed; bottom: 95px; right: 25px; width: 360px; max-width: 90vw; height: 500px; max-height: 80vh; z-index: 9999; background: #ffffff; border: 1px solid rgba(0,0,0,0.1); flex-direction: column;">
    
    <!-- Header -->
    <div class="bg-primary text-white p-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                <i class="fa-solid fa-robot fa-lg"></i>
            </div>
            <div>
                <h6 class="m-0 fw-bold">CoolBot Assistant</h6>
                <small class="text-white-50" style="font-size: 0.75rem;"><i class="fa-solid fa-circle text-success me-1"></i> Online | Car Aircon Help</small>
            </div>
        </div>
        <button type="button" class="btn-close btn-close-white" onclick="toggleChatbot()"></button>
    </div>

    <!-- Messages Container -->
    <div id="chat-messages" class="p-3 overflow-y-auto flex-grow-1" style="background-color: #f8f9fa; display: flex; flex-direction: column; gap: 10px;">
        <!-- Bot Welcome Message -->
        <div class="d-flex align-items-start gap-2">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="bg-white p-3 rounded-3 shadow-sm border text-dark" style="max-width: 85%; font-size: 0.88rem;">
                Good day! I am <strong>CoolBot</strong>. How can I assist you with your car aircon today?
            </div>
        </div>
    </div>

    <!-- Floating Quick Prompts Buttons Container -->
    <div class="p-2 border-top bg-light d-flex flex-wrap gap-1 align-items-center justify-content-start" style="max-height: 120px; overflow-y: auto;">
        <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 shadow-sm floating-prompt-btn" style="font-size: 0.75rem; transition: all 0.2s ease;" onclick="sendQuickReply('How much is the check-up?')">💰 Free Check-up</button>
        <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 shadow-sm floating-prompt-btn" style="font-size: 0.75rem; transition: all 0.2s ease;" onclick="sendQuickReply('What are your services and rates?')">📋 Pricelist</button>
        <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 shadow-sm floating-prompt-btn" style="font-size: 0.75rem; transition: all 0.2s ease;" onclick="sendQuickReply('Why is my aircon not cooling?')">❄️ Why not cooling?</button>
        <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 shadow-sm floating-prompt-btn" style="font-size: 0.75rem; transition: all 0.2s ease;" onclick="sendQuickReply('How do I book an appointment?')">📅 How to book?</button>
    </div>

    <!-- Input Form -->
    <div class="p-2 border-top bg-white d-flex gap-2 align-items-center">
        <input type="text" id="chat-input" class="form-control form-control-sm border-1 rounded-pill px-3" placeholder="Type your question..." onkeypress="handleChatKeyPress(event)">
        <button class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" style="width: 34px; height: 34px;" onclick="sendMessage()">
            <i class="fa-solid fa-paper-plane"></i>
        </button>
    </div>
</div>

<style>
    .floating-prompt-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    }
</style>

<script>
    function toggleChatbot() {
        const chatBox = document.getElementById('chat-box');
        if (chatBox.classList.contains('d-none')) {
            chatBox.classList.remove('d-none');
            chatBox.style.display = 'flex';
        } else {
            chatBox.classList.add('d-none');
            chatBox.style.display = 'none';
        }
    }

    function handleChatKeyPress(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    }

    function sendQuickReply(text) {
        document.getElementById('chat-input').value = text;
        sendMessage();
    }

    function sendMessage() {
        const input = document.getElementById('chat-input');
        const query = input.value.trim();
        if (!query) return;

        appendMessage(query, 'user');
        input.value = '';

        // Show typing indicator
        const typingId = appendTypingIndicator();

        setTimeout(() => {
            removeTypingIndicator(typingId);
            const response = getBotResponse(query.toLowerCase());
            appendMessage(response, 'bot');
        }, 600);
    }

    function appendMessage(text, sender) {
        const messagesDiv = document.getElementById('chat-messages');
        const wrapper = document.createElement('div');

        if (sender === 'user') {
            wrapper.className = 'd-flex justify-content-end mb-2';
            wrapper.innerHTML = `
                <div class="bg-primary text-white p-2 px-3 rounded-3 shadow-sm" style="max-width: 85%; font-size: 0.88rem;">
                    ${escapeHtml(text)}
                </div>
            `;
        } else {
            wrapper.className = 'd-flex align-items-start gap-2 mb-2';
            wrapper.innerHTML = `
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">
                    <i class="fa-solid fa-robot"></i>
                </div>
                <div class="bg-white p-3 rounded-3 shadow-sm border text-dark" style="max-width: 85%; font-size: 0.88rem;">
                    ${text}
                </div>
            `;
        }

        messagesDiv.appendChild(wrapper);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
    }

    function appendTypingIndicator() {
        const messagesDiv = document.getElementById('chat-messages');
        const id = 'typing-' + Date.now();
        const wrapper = document.createElement('div');
        wrapper.id = id;
        wrapper.className = 'd-flex align-items-start gap-2 mb-2';
        wrapper.innerHTML = `
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px; font-size: 12px;">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="bg-white p-2 px-3 rounded-3 shadow-sm border text-muted" style="font-size: 0.8rem;">
                <i class="fa-solid fa-circle-notch fa-spin me-1"></i> Thinking of an answer...
            </div>
        `;
        messagesDiv.appendChild(wrapper);
        messagesDiv.scrollTop = messagesDiv.scrollHeight;
        return id;
    }

    function removeTypingIndicator(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text;
        return div.innerHTML;
    }

    function getBotResponse(q) {
        // Knowledge Base Rules
        if (q.includes('price') || q.includes('cost') || q.includes('rate') || q.includes('pricelist') || q.includes('how much') || q.includes('presyo') || q.includes('magkano')) {
            if (q.includes('compressor')) {
                return "The <strong>Replace Compressor</strong> service costs <strong>₱12,000</strong> (includes labor and standard unit replacement).";
            }
            if (q.includes('expansion') || q.includes('valve')) {
                return "The <strong>Replace Expansion Valve</strong> service costs <strong>₱1,500</strong>.";
            }
            if (q.includes('drier')) {
                return "The <strong>Replace Drier</strong> service costs <strong>₱1,200</strong>.";
            }
            if (q.includes('evaporator')) {
                return "The <strong>Replace Evaporator</strong> service costs <strong>₱4,500</strong>.";
            }
            if (q.includes('condenser')) {
                return "The <strong>Replace Condenser</strong> service costs <strong>₱4,000</strong>.";
            }
            if (q.includes('flashing') || q.includes('freon') || q.includes('clean') || q.includes('linis')) {
                return "The <strong>Flashing System</strong> service (internal system flushing, oil replacement, and Freon recharge) costs <strong>₱1,500</strong>.";
            }
            return `Here is our official list of services and rates:<br><br>
            • <strong>Aircon Check-up:</strong> FREE<br>
            • <strong>Replace Compressor:</strong> ₱12,000<br>
            • <strong>Replace Expansion Valve:</strong> ₱1,500<br>
            • <strong>Replace Drier:</strong> ₱1,200<br>
            • <strong>Replace Evaporator:</strong> ₱4,500<br>
            • <strong>Replace Condenser:</strong> ₱4,000<br>
            • <strong>Flashing System:</strong> ₱1,500`;
        }

        if (q.includes('checkup') || q.includes('check-up') || q.includes('check up') || q.includes('free') || q.includes('inspection') || q.includes('libre')) {
            return "Our <strong>Aircon Check-up & Diagnosis</strong> is <strong>100% FREE</strong>! You can book a slot to have our technicians inspect system pressure, Freon levels, and compressor performance.";
        }

        if (q.includes('cold') || q.includes('cool') || q.includes('hot') || q.includes('warm') || q.includes('broken') || q.includes('leak') || q.includes('why') || q.includes('malamig') || q.includes('mainit')) {
            return "Common reasons why your car aircon is not cooling effectively:<br>1. <strong>Low Freon level</strong> or system leaks.<br>2. <strong>Dirty Evaporator</strong> or cabin air filter.<br>3. <strong>Faulty Compressor</strong> or Expansion Valve.<br><br>We recommend booking our <strong>Free Aircon Check-up</strong> so our team can pinpoint the exact cause.";
        }

        if (q.includes('book') || q.includes('sched') || q.includes('appointment') || q.includes('how')) {
            return "Booking an appointment is fast and easy!<br>1. <strong>Log in</strong> to your account.<br>2. Click the <strong>Book Appointment</strong> button.<br>3. Choose your date and click the services you need.";
        }

        if (q.includes('hours') || q.includes('time') || q.includes('open') || q.includes('days') || q.includes('schedule') || q.includes('oras')) {
            return "We are open from <strong>Monday through Saturday (8:00 AM – 5:00 PM)</strong>. Appointment slots are available daily.";
        }

        if (q.includes('location') || q.includes('where') || q.includes('address') || q.includes('shop') || q.includes('saan')) {
            return "Our service center is located along the main service highway. Complete directions and map guidance will be provided upon booking confirmation.";
        }

        if (q.includes('thank') || q.includes('thanks') || q.includes('ok') || q.includes('salamat')) {
            return "You're very welcome! I'm here to help anytime. Wishing you cool and comfortable drives ahead! ❄️";
        }

        return "I'm sorry, I didn't quite catch that. Feel free to ask me about:<br>• <strong>Pricelist</strong> or service rates<br>• <strong>Free Check-up</strong><br>• <strong>How to book</strong> an appointment<br>• Common reasons why your aircon is <strong>not cooling</strong>";
    }
</script>