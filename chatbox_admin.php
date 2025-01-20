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

        function loadChatHistory(username) {
            fetch('getMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user: username,
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Display chat history
                    data.messages.forEach(message => {
                        const messageElement = document.createElement('div');
                        messageElement.classList.add('message', message.sender === 'admin' ? 'sent' : 'received');
                        messageElement.textContent = message.message;
                        adminChatMessages.appendChild(messageElement);
                    });

                    // Scroll to the last message
                    adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
                }

                else {
                    console.error('Failed to load chat history');
                }
            });
            // Clear previous messages
            //adminChatMessages.innerHTML = '';

            // Simulated chat history (in real case, fetch from server)
            //const chatHistory = getChatHistoryFromServer(username);

            // Display chat history
            // chatHistory.forEach(message => {
                // const messageElement = document.createElement('div');
                // messageElement.classList.add('message', message.sender === 'admin' ? 'sent' : 'received');
                // messageElement.textContent = message.text;
                // adminChatMessages.appendChild(messageElement);
            // });

            // Scroll to the last message
            //adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
        }

        // Function to simulate fetching chat history from the server
        // function getChatHistoryFromServer(username) {
            //Example chat history (in real case, fetch from server)
            // if (username === 'user1') {
                // return [
                    // { sender: 'user', text: 'Hello, I need help with my account.' },
                    // { sender: 'admin', text: 'Hello, how can I assist you?' }
                // ];
            // } else {
                // return [];
            // }
        // }
        
        // Fungsi untuk mengirim pesan
        adminSendBtn.addEventListener('click', () => {
            const messageText = adminChatInput.value.trim();
            const selectedUser = userSelect.value;
            if (messageText) {
                // Menambahkan pesan admin ke chatbox
                const adminMessage = document.createElement('div');
                adminMessage.classList.add('message', 'sent');
                adminMessage.textContent = messageText;
                adminChatMessages.appendChild(adminMessage);

                // Simpan ke database atau kirim ke backend
                sendMessageToServer(username, selectedUser, messageText);
    
                // Bersihkan input
                adminChatInput.value = '';
    
                // Scroll ke pesan terakhir
                adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
            }
        });

        // Minimize chatbox and sidebar
        //minimizeChatboxBtn = document.getElementById('minimize-chatbox');
        minimizeChatboxBtn.addEventListener('click', () => {
            adminChatbox.style.display = 'none'; // Sembunyikan chatbox
            userListContainer.style.display = 'none'; // Sembunyikan daftar     pengguna
            minimizedChatIcon.style.display = 'block'; // Tampilkan ikon    minimized
            adminChatInterface.style.display = 'none'; // Sembunyikan seluruh   chat interface
        });

        // Fungsi untuk menampilkan pesan yang diterima oleh admin
        // function showReceivedMessage(message) {
            // const userMessage = document.createElement('div');
            // userMessage.classList.add('message', 'received');
            // userMessage.textContent = message;
            // adminChatMessages.appendChild(userMessage);
    
            // adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
        // }
    
        //Simulasi menerima pesan dari user
        // function simulateIncomingMessage() {
            // setTimeout(() => {
                // showReceivedMessage('Hello admin, I need assistance.');
            // }, 2000);
        // }
    
        // Fungsi untuk mengirim pesan ke server (misalnya melalui AJAX)
        function sendMessageToServer(sender, receiver, message) {
            fetch('sendMessage.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    sender: sender,
                    receiver: receiver,
                    message: message,
                })
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
            // setTimeout(() => {
                //Simulasi menerima pesan dari server
                // const responseMessage = 'Thank you for your message. Our team will assist you shortly.';
                // displayReceivedMessage(responseMessage);
            // }, 1000);
        }

        // Fungsi untuk menampilkan pesan yang diterima dari user
        function displayReceivedMessage(message) {
            const userMessage = document.createElement('div');
            userMessage.classList.add('message', 'received');
            userMessage.textContent = message;
            adminChatMessages.appendChild(userMessage);
    
            // Scroll ke pesan terakhir
            adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
        }
    });
</script>
</html>