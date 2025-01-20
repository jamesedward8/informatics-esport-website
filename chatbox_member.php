<?php 
    require_once('dbparent.php');
    $isLoggedIn = isset($_SESSION['username']);
?>
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
            <div class="chatbox-body" id="chat-messages" data-username="<?php echo $_SESSION['username']; ?>">
                <!-- Messages will appear here -->
            </div>
            <div class="chatbox-input">
                <input type="text" id="chat-input" placeholder="Type your message...">
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
        const chatBox = document.getElementById('chatbox');
        const closeChatbox = document.getElementById('close-chatbox');
        const sendBtn = document.getElementById('send-btn');
        const selectedName = document.getElementById('selected-name');
        const chatMessages = document.getElementById('chat-messages');
        const username = chatMessages.getAttribute('data-username');
        const chatInput = document.getElementById('chat-input');

        let chatboxOpen = false; // Flag to check if the chatbox is open

        // Open chatbox
        chatIcon.addEventListener('click', () => {
            chatBox.style.display = 'flex';
            document.getElementById('chat-icon').style.display = 'none';
            chatboxOpen = true;
            loadChatHistory(username);
            //startFetchingMessages(username);
        });

        // Close chatbox
        closeChatbox.addEventListener('click', () => {
            chatBox.style.display = 'none';
            document.getElementById('chat-icon').style.display = 'block';
            chatboxOpen = false;
            //stopFetchingMessages(); // Stop periodic fetching
        });

        // Send message
        sendBtn.addEventListener('click', () => {
            const messageText = chatInput.value.trim();
            if (messageText) {
                //appendMessage(username, messageText, true); // Append user's message immediately
                sendMessageToServer(username, messageText);
                chatInput.value = '';
            }
        });

        // Load chat history initially
        function loadChatHistory(username) {
            fetchMessages(username, true);
        }

        function fetchMessages(username) {
            fetch('getMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ user: username })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    const messages = data.messages;

                    let currentDisplayedDate = '';

                    if (messages.length == 0) {
                        const welcomeMessage = "Welcome to our customer service! How can i assist you today?";
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message', 'received');
                        messageElement.textContent = welcomeMessage;

                        chatMessages.appendChild(messageElement);
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    }

                    else {
                        messages.forEach(message => {
                            const messageDate = new Date(message.msg_time);
                            const formattedDate = messageDate.toLocaleDateString('en-US', {
                                weekday: 'long',
                                day: 'numeric',
                                month: 'long'
                            });

                            if (formattedDate != currentDisplayedDate) {
                                currentDisplayedDate = formattedDate;

                                const dateHeader = document.createElement('div');
                                dateHeader.classList.add('date-header');
                                dateHeader.textContent = currentDisplayedDate;
                                chatMessages.appendChild(dateHeader);
                            }

                            appendMessage(
                                message.sender,
                                message.message,
                                message.sender === username,
                                message.msg_time
                            );
                        });
                    }
                } else {
                    console.error('Error loading messages:', data.message);
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
        }

        function appendMessage(sender, text, isSent, timestamp) {
            // Generate a unique identifier using sender and timestamp
            const uniqueId = `${sender}-${timestamp}`;

            // Check if the message already exists in the chatbox by ID
            if (document.getElementById(uniqueId)) return;

            const messageElement = document.createElement('div');
            messageElement.classList.add('message', isSent ? 'sent' : 'received');
            messageElement.id = uniqueId;  // Set unique ID

            // Add message content
            const messageText = document.createElement('div');
            messageText.textContent = text;
            messageText.classList.add('message-text');
            messageElement.appendChild(messageText);

            // Add formatted timestamp
            const messageTime = document.createElement('div');
            const formattedTime = new Date(timestamp).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
            messageTime.textContent = formattedTime;
            messageTime.classList.add('message-time');
            messageElement.appendChild(messageTime);

            // Append the message to the chatbox
            chatMessages.appendChild(messageElement);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        function sendMessageToServer(sender, message) {
            const timestamp = new Date().toISOString();  // Generate timestamp
            appendMessage(sender, message, true, timestamp);  // Append with timestamp

            // Send message to the server
            fetch('sendMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ sender: sender, receiver: 'admin', message: message, timestamp: timestamp })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status !== 'success') {
                    console.error('Error sending message:', data.message);
                }
            })
            .catch(error => console.error('Error sending message:', error));
        }

        // Display a received message
        function displayReceivedMessage(message) {
            appendMessage('admin', message, false);
        }
    });
</script>
</html>