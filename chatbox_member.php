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
                <span id="selected-name">Chat with Customer Service</span>
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
        const selectedName = document.getElementById('selected-name');
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

        // Simulasi memilih customer service
        selectedName.textContent = 'Chat with Customer Service';

        // Fungsi untuk mengirim pesan oleh user
        sendBtn.addEventListener('click', () => {
            const messageText = chatInput.value.trim();
            if (messageText) {
                const userMessage = document.createElement('div');
                userMessage.textContent = messageText;
                userMessage.classList.add('message', 'sent');
                chatMessages.appendChild(userMessage);

                // Kirim pesan ke customer server
                sendMessageToServer('user', messageText);

                // Bersihkan input
                chatInput.value = '';

                // Scroll ke pesan terakhir
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        });

        function sendMessageToServer(sender, message) {
            // Kirim pesan ke server
            console.log('Message sent from ${sender}: ${message}');

            setTimeout(() => {
                // Simulasi menerima pesan dari server
                const responseMessage = 'Thank you for your message. Our team will assist you shortly.'
                displayReceivedMessage(responseMessage);
            }, 1000);
        }   

        // Fungsi untuk menampilkan pesan dari customer service
        function displayReceivedMessage(message) {
            const receivedMessage = document.createElement('div');
            receivedMessage.textContent = message;
            receivedMessage.classList.add('message', 'received');
            chatMessages.appendChild(receivedMessage);

            // Scroll ke pesan terakhir
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }     
    });
</script>
</html>