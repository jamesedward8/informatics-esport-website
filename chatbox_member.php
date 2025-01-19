<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Only show chatbox if the user is logged in -->
    <?php if ($isLoggedIn): ?>
        <!-- Minimized Chat Icon -->
        <div id="chat-icon" class="chat-icon">
            <button type="submit" id="open-chatbox" class="chat-icon-btn">
                <i class="fa-regular fa-message"></i>
            </button>
        </div>
        <!-- Chatbox -->
        <div id="chatbox" class="chatbox-container">
            <div class="chatbox-header">
                <span>Ask Us Here</span>
                <button id="close-chatbox" class="close-btn">&times;</button>
            </div>
            <div class="chatbox-body">
                <div class="chat-messages" id="chat-messages">
                    <!-- Messages will appear here -->
                </div>
            </div>
            <div class="chatbox-input">
                <input type="text" id="chat-input" placeholder="Type your message..." />
                <button id="send-btn" class="send-btn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </div>      
    <?php endif; ?>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatIcon = document.getElementById('open-chatbox');
        const chatbox = document.getElementById('chatbox');
        const closeChatbox = document.getElementById('close-chatbox');
        const sendBtn = document.getElementById('send-btn');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        // Open chatbox
        chatIcon.addEventListener('click', () => {
            chatbox.style.display = 'flex';
            document.getElementById('chat-icon').style.display = 'none';
        });
        // Close chatbox
        closeChatbox.addEventListener('click', () => {
            chatbox.style.display = 'none';
            document.getElementById('chat-icon').style.display = 'block';
        });
        // Send message
        sendBtn.addEventListener('click', () => {
            const message = chatInput.value.trim();
            if (message) {
                const messageElement = document.createElement('div');
                messageElement.textContent = message;
                messageElement.style.cssText =
                    'background:#f16c20;color:white;padding:5px 10px;border-radius:5px;align-self:end;';
                chatMessages.appendChild(messageElement);
                chatInput.value = '';
            }
        });
    });
</script>
</html>