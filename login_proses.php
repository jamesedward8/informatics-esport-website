<?php 
    session_start();
    require_once('memberClass.php');

    if (isset($_POST['btnLogin'])) {
        $member = new Member();

        extract($_POST);
        $user = $member->login($username, $password);

        if ($user) {
            $_SESSION['idmember'] = $user['idmember'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile'] = $user['profile'];
            echo "<script>window.location.href='index.php?login=success';</script>";
        } 
        else {
            echo "<script>alert('Incorrect username or password!'); window.location.href='login.php';</script>";
        }
    }
    $mysqli->close();
?>
