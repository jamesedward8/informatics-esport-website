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
            <div id="user-list-container" class="user-list" style="display: none;       ">
                <h3>Users</h3>
                <ul id="user-list">
                    <!-- Daftar pengguna akan dimuat di sini -->
                    <li class="user-item" data-username="user1">User 1</li>
                    <li class="user-item" data-username="user2">User 2</li>
                    <li class="user-item" data-username="user3">User 3</li>
                    <!-- Tambahkan lebih banyak pengguna sesuai kebutuhan -->
                </ul>
            </div>

            <!-- Main Chatbox (disembunyikan secara default) -->
            <div id="admin-chatbox" class="admin-chatbox" style="display: none;">
                <!-- Header Chatbox -->
                <div class="admin-chat-header">
                    <span id="selected-user-name">Select a user</span>
                    <div class="chat-controls">
                        <!-- Tombol minimize -->
                        <button id="minimize-chatbox" class="minimize-btn">&times;      </button>
                    </div>
                </div>

                <!-- Body Chatbox -->
                <div id="admin-chat-messages" class="admin-chat-body">
                    <!-- Pesan akan muncul di sini -->
                </div>

                <!-- Input Chatbox -->
                <div class="admin-chat-input">
                    <input type="text" id="admin-chat-input" placeholder="Type your message..." disabled />
                    <button id="admin-send-btn" disabled>Send</button>
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
        const minimizedChatIcon = document.getElementById   ('minimized-chat-icon');
        const userListContainer = document.getElementById   ('user-list-container');
        const userListItems = document.querySelectorAll('.user-item');
        const minimizeChatboxBtn = document.getElementById  ('minimize-chatbox');
        const restoreChatboxBtn = document.getElementById('restore-chatbox');
        const selectedUserName = document.getElementById('selected-user-name');
        const adminChatInput = document.getElementById('admin-chat-input');
        const adminSendBtn = document.getElementById('admin-send-btn');

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

        // Menangani klik pada daftar pengguna
        userListItems.forEach(item => {
            item.addEventListener('click', function () {
                const username = this.getAttribute('data-username'); // Ambil   nama user yang dipilih

                // Menandai user yang dipilih
                userListItems.forEach(user => {
                    user.classList.remove('active');
                });
                this.classList.add('active'); // Tandai yang dipilih

                // Menampilkan percakapan dengan user yang dipilih
                selectedUserName.textContent = `Chatting with ${username}`;
                adminChatbox.style.display = 'flex'; // Menampilkan chatbox
                adminChatInput.disabled = false; // Mengaktifkan textbox            
                adminSendBtn.disabled = true; // Mengaktifkan tombol send

                // Simulasikan percakapan (bisa diganti dengan data dari    backend)
                const adminMessage = document.createElement('div');
                adminMessage.classList.add('message', 'sent');
                adminMessage.textContent = `Hello, ${username}! How can I   help you today?`;
                document.getElementById('admin-chat-messages').appendChild  (adminMessage);
            });
        });

        // Fungsi untuk mengirim pesan
        adminSendBtn.addEventListener('click', () => {
            const messageText = adminChatInput.value.trim();
            if (messageText) {
                // Menambahkan pesan admin ke chatbox
                const adminMessage = document.createElement('div');
                adminMessage.classList.add('message', 'sent');
                adminMessage.textContent = messageText;
                document.getElementById('admin-chat-messages').appendChild(adminMessage);
    
                // Bersihkan input
                adminChatInput.value = '';
    
                // Scroll ke pesan terakhir
                document.getElementById('admin-chat-messages').scrollTop = document.getElementById('admin-chat-messages').scrollHeight;
            }
        });

        // Minimize chatbox and sidebar
        minimizeChatboxBtn = document.getElementById  ('minimize-chatbox');
        minimizeChatboxBtn.addEventListener('click', () => {
            adminChatbox.style.display = 'none'; // Sembunyikan chatbox
            userListContainer.style.display = 'none'; // Sembunyikan daftar     pengguna
            minimizedChatIcon.style.display = 'block'; // Tampilkan ikon    minimized
            adminChatInterface.style.display = 'none'; // Sembunyikan seluruh   chat interface
        });
    });
</script>
</html>