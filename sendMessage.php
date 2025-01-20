<?php 
    require_once('dbparent.php');

    header('Content-Type: application/json');

    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true); 
        // Menggunakan json_decode, bukan json_encode
        $sender = $data['sender'];
        $receiver = $data['receiver'];
        $message = $data['message'];

        // Validate data
        if (empty($sender) || empty($receiver) || empty($message)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing data']);
            exit;
        }

        try {
            if ($receiver === 'admin') {
                // Ambil semua admin dari database
                $stmt = $mysqli->prepare('SELECT username FROM member WHERE profile = "admin";');
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result) {
                    while ($row = $result->fetch_assoc()) {
                        $adminUsername = $row['username'];
                        // Insert message
                        $stmt = $mysqli->prepare('INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?);');
                        $stmt->bind_param("sss", $sender, $receiver, $message);
                        $stmt->execute();
                    }
                }

                else {
                    throw new Exception('No admin found');
                }
            }

            else {
                // Insert message
                $stmt = $mysqli->prepare('INSERT INTO messages (sender, receiver, message) VALUES (?, ?, ?);');
                $stmt->bind_param("sss", $sender, $receiver, $message);
                $stmt->execute();
            }

            // Validasi insert
            echo json_encode(['status' => 'success', 'message' => 'Message sent successfully']);
        }
        
        catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    }
?>