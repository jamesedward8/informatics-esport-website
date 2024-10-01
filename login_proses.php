<?php 
    session_start();
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $user = "admin";

    if (isset($_POST['btnLogin'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("SELECT * FROM member WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $res = $stmt->get_result();

        if($res->num_rows > 0) {
            $row = $res->fetch_assoc();
            if(password_verify($password, $row['password'])) {
                $_SESSION['username'] = $row['username'];
                echo "<script>alert('Login successful!'); window.location.href='home.php';</script>";
            } 
            else {
                echo "<script>alert('Incorrect username or password!'); window.history.back();</script>";
            }
        } 
        else {
            echo "<script>alert('User not found!'); window.history.back();</script>";
        }
    }

    $mysqli->close();