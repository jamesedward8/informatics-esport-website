<?php 
    require_once('dbparent.php');

    $db = new DBParent();
    $mysqli = $db->mysqli;

    if (!isset($mysqli)) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection is not established']);
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (!isset($data['user']) || empty($data['user'])) {
            echo json_encode(['status' => 'error', 'message' => 'No specified user']);
            exit;
        }
    
        $user = $data['user'];
        $cs = "admin";
    
        try {
            $stmt = $mysqli->prepare('SELECT * FROM messages WHERE (sender = ? AND receiver = ?) OR (sender = ? AND receiver = ?) ORDER BY msg_time ASC;');
    
            if (!$stmt) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $mysqli->error]);
                exit;
            }
    
            $stmt->bind_param('ssss', $user, $cs, $cs, $user);
            $stmt->execute();
            $result = $stmt->get_result();
    
            if ($result->num_rows === 0) {
                echo json_encode(['status' => 'success', 'messages' => [], 'message' => 'No messages found']);
                exit;
            }
    
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