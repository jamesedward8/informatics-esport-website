<?php 
    session_start();

    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;

    $idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;
    $idmember = isset($_GET['idmember']) ? $_GET['idmember'] : null;
    $chosen_role = isset($_GET['role-chosen']) ? $_GET['role-chosen'] : null;

    $result = isset($_GET['result']) ? $_GET['result'] : null;

    if ($idteam && $idmember && ($result == "approved" || $result == "rejected")) {
        if ($result == "approved") {
            $stmt1 = $mysqli->prepare("
                UPDATE join_proposal 
                SET status = 'rejected' 
                WHERE idmember = ? AND idteam != ? AND idteam 
                IN (
                    SELECT idteam FROM team WHERE idgame = (
                    SELECT idgame FROM team WHERE idteam = ?)
                )
            ");
            $stmt1->bind_param("iii", $idmember, $idteam, $idteam);
            $stmt1->execute();
            $stmt1->close();
        }

        $stmt = $mysqli->prepare("UPDATE join_proposal SET status = ? WHERE idteam = ? AND idmember = ?");
        $stmt->bind_param("sii", $result, $idteam, $idmember);

        if ($stmt->execute()) {
            if ($result == "approved") {
                $stmt2 = $mysqli->prepare("INSERT INTO team_members (idteam, idmember, description) VALUES (?, ?, ?)");
                $stmt2->bind_param("iis", $idteam, $idmember, $chosen_role);
                $stmt2->execute();
                $stmt2->close();
            }
            
            echo "<script>alert('Join proposal has been $result!'); window.location.href='join_team_decide.php';</script>";
        } 
        else {
            echo "<script>alert('Error: " . $mysqli->error . "'); window.history.back();</script>";
        }
        $stmt->close();
    } 
    else {
        echo "<script>alert('Invalid Parameters!'); window.history.back();</script>";
    }
    $mysqli->close();
?>