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
        <div id="minimized-chat-icon" class="chat-icon">
            <button id="restore-chatbox" class="chat-icon-btn">
                <i class="fa-regular fa-message"></i>
            </button>
        </div>

        <!-- Admin Chat Interface -->
        <div id="admin-chat-interface" class="admin-chat-container">
            <!-- Sidebar: Daftar Pengguna (disembunyikan secara default) -->
            <div id="user-list-container" class="user-list" style="display: none;">
                <!-- Combo Box (Dropdown) untuk memilih user -->
                <select id="user-select">
                    <option value="" disabled selected>Select a user</option>
                    <?php 
                        $stmt_users = $mysqli->prepare('SELECT username FROM member WHERE profile = "member";');
                        $stmt_users->execute();
                        $result_users = $stmt_users->get_result();
                        while ($row = $result_users->fetch_assoc()) {
                            echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                        }
                    ?>
                </select>
            </div>

            <!-- Main Chatbox (disembunyikan secara default) -->
            <div id="admin-chatbox" class="admin-chatbox" style="display: none;">
                <!-- Header Chatbox -->
                <div class="admin-chat-header">
                    <span id="selected-user-name">Select a user</span>
                    <div class="chat-controls">
                        <button id="minimize-chatbox" class="minimize-btn">&times;</button>
                    </div>
                </div>

                <!-- Body Chatbox -->
                <div id="admin-chat-messages" class="admin-chat-body" data-username="<?php echo $_SESSION['username']; ?>">
                    <!-- Pesan akan muncul di sini -->
                </div>

                <!-- Input Chatbox -->
                <div class="admin-chat-input">
                    <input type="text" id="admin-chat-input" placeholder="Type your message...">
                    <button id="admin-send-btn">Send</button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const adminChatInterface = document.getElementById('admin-chat-interface');
        const adminChatbox = document.getElementById('admin-chatbox');
        const minimizedChatIcon = document.getElementById('minimized-chat-icon');
        const userListContainer = document.getElementById('user-list-container');
        const userSelect = document.getElementById('user-select');
        const minimizeChatboxBtn = document.getElementById  ('minimize-chatbox');
        const restoreChatboxBtn = document.getElementById('restore-chatbox');
        const selectedUserName = document.getElementById('selected-user-name');
        const adminChatInput = document.getElementById('admin-chat-input');
        const adminSendBtn = document.getElementById('admin-send-btn');
        const adminChatMessages = document.getElementById('admin-chat-messages');
        const username = adminChatMessages.getAttribute('data-username');

        // Default: Minimize chatbox and sidebar on page load
        adminChatInterface.style.display = 'none'; // Sembunyikan chatbox

        // Minimize chatbox and sidebar
        minimizeChatboxBtn.addEventListener('click', () => {
            adminChatbox.style.display = 'none'; // Sembunyikan chatbox
            userListContainer.style.display = 'none'; // Sembunyikan daftar pengguna
            adminChatInterface.style.display = 'none'; // Sembunyikan seluruh chat interface
            minimizedChatIcon.style.display = 'block'; // Tampilkan ikon minimized
        });

        // Restore chatbox and sidebar
        restoreChatboxBtn.addEventListener('click', () => {
            adminChatMessages.innerHTML = '';
            adminChatbox.style.display = 'flex'; // Kembalikan chatbox
            userListContainer.style.display = 'flex'; // Tampilkan daftar   pengguna
            adminChatInterface.style.display = 'flex'; // Tampilkan seluruh     chat interface
            minimizedChatIcon.style.display = 'none'; // Sembunyikan ikon  minimized
        });

        // Menangani pemilihan user melalui combo box (dropdown)
        userSelect.addEventListener('change', function () {
            const username = this.value; // Ambil nama user yang dipilih

            // Menandai user yang dipilih
            selectedUserName.textContent = `Chatting with ${username}`;
            adminChatbox.style.display = 'flex'; // Menampilkan chatbox
            adminChatMessages.innerHTML = ''; // Bersihkan chat history
            adminChatInput.disabled = false; // Mengaktifkan textbox
            adminSendBtn.disabled = false; // Mengaktifkan tombol send

            // Load histori chat yang pernah ada
            loadChatHistory(username);

            // Simulasikan percakapan awal dengan user baru (atau gunakan data asli dari backend)
            // const welcomeMessage = document.createElement('div');
            // welcomeMessage.classList.add('message', 'sent');
            // welcomeMessage.textContent = `Hello, ${username}! How can I help you today?`;
            // adminChatMessages.appendChild(welcomeMessage);

            // Scroll ke pesan terakhir
            // adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
        });

        // Fungsi untuk mengirim pesan
        adminSendBtn.addEventListener('click', () => {
            const messageText = adminChatInput.value.trim();
            const selectedUser = userSelect.value;
            if (messageText) {
                // Menambahkan pesan admin ke chatbox
                const adminMessage = document.createElement('div');
                adminMessage.classList.add('message', 'sent');
                adminMessage.textContent = messageText;
                //adminChatMessages.appendChild(adminMessage);
                // Simpan ke database atau kirim ke backend
                sendMessageToServer(username, selectedUser, messageText);
                // Bersihkan input
                adminChatInput.value = '';
                // Scroll ke pesan terakhir
                adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
            }
        });

        function loadChatHistory(username) {
            fetchMessages(username);
        }

        function fetchMessages(username) {
            fetch('getMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({user: username})
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
                            adminChatMessages.appendChild(dateHeader);
                        }
                        
                        appendMessage(
                            message.sender, 
                            message.message, 
                            message.sender === 'admin', 
                            message.msg_time
                        );
                    });
                }
    
                else {
                    console.error('Error loading messages:', data.message);
                }
            })
            .catch(error => console.error('Error fetching messages:', error));
        }

        function appendMessage(sender, text, isSent, timestamp) {

            const uniqueId = `${sender}-${timestamp}`;

            if (document.getElementById(uniqueId)) return;

            const messageElement = document.createElement('div');
            messageElement.classList.add('message', isSent ? 'sent' : 'received');
            messageElement.id = uniqueId;

            const messageText = document.createElement('div');
            messageText.textContent = text;
            messageText.classList.add('message-text');
            messageElement.appendChild(messageText);

            const messageTime = document.createElement('div');
            const formattedTime = new Date(timestamp).toLocaleTimeString('en-US', {
                hour: '2-digit',
                minute: '2-digit'
            });
            messageTime.textContent = formattedTime;
            messageTime.classList.add('message-time');
            messageElement.appendChild(messageTime);

            adminChatMessages.appendChild(messageElement);
            adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
        }
    
        // Fungsi untuk mengirim pesan ke server (misalnya melalui AJAX)
        function sendMessageToServer(sender, receiver, message) {
            const timestamp = new Date().toISOString();
            appendMessage(sender, message, true, timestamp);

            fetch('sendMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({sender: sender, receiver: receiver, message: message, timestamp})
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Message sent from ${sender} to ${receiver}: ${message}');
                }
    
                else {
                    console.error('Error sending message:', data.message);
                }
            });
        }

        // Fungsi untuk menampilkan pesan yang diterima dari user
        function displayReceivedMessage(message) {
            appendMessage(selectedUser, message, false);
        }
    });
</script>
</html>