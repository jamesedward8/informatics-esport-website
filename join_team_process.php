<?php 
session_start();

$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

if (!$idmember) {
    echo "<script>alert('You must be logged in to join a team.'); window.location.href='login.php'; </script>";
    exit();
}

if (isset($_POST['btn-join'])) {
    $idteam = isset($_POST['idteam']) ? $_POST['idteam'] : null;
    $role_in_game = isset($_POST['role']) ? $_POST['role'] : null;

    if ($idteam && $idmember && $role_in_game) {
        // Insert the join proposal directly with idteam
        $stmt = $mysqli->prepare("INSERT INTO join_proposal (idmember, idteam, description, status) VALUES (?, ?, ?, ?);");
        $status = "Waiting";
        $stmt->bind_param("iiss", $idmember, $idteam, $role_in_game, $status);

        if ($stmt->execute()) {
            echo "<script>alert('Your join proposal is being processed, please wait!'); window.location.href='team.php'; </script>";
        } else {
            echo "<script>alert('Error: " . $mysqli->error . "'); window.history.back();</script>";
        }
    } 
    
    else {
        echo "<script>alert('Please complete all fields.'); window.history.back();</script>";
    }
}

?>