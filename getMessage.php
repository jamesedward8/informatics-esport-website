<?php 
    require_once('dbparent.php');

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // Decode the incoming JSON request
        $data = json_decode(file_get_contents('php://input'), true);

        // Debugging: Log the received data
        error_log('Data received: ' . print_r($data, true));

        // Check if user is provided
        $user = $data['user'];

        if (empty($user)) {
            echo json_encode(['status' => 'error', 'message' => 'No specified user']);
            exit;
        }

        try {
            // Prepare the query
            $stmt = $mysqli->prepare('SELECT id, sender, receiver, message, msg_time FROM messages WHERE (sender = ? AND receiver   = ?) OR (sender = ? AND receiver = ?) ORDER BY msg_time ASC;');

            if (!$stmt) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $mysqli->error]);
                exit;
            }

            // Bind parameters and execute query
            $stmt->bind_param('ssss', $user, 'admin', 'admin', $user);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if there are no messages
            if ($result->num_rows === 0) {
                echo json_encode(['status' => 'success', 'messages' => [], 'message' => 'No messages found']);
                exit;
            }

            // Fetch all messages
            $messages = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode(['status' => 'success', 'messages' => $messages]);
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request method. Use POST instead.',
            'method' => $_SERVER['REQUEST_METHOD']
        ]);
    }
?>