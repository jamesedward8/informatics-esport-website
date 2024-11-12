<?php 
    session_start();
    require_once("proposalClass.php");
    require_once("dbparent.php");

    $db = new DBParent(); 
    $mysqli = $db->mysqli;

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;
    $idmember = isset($_GET['idmember']) ? $_GET['idmember'] : null;
    $chosen_role = isset($_GET['role-chosen']) ? $_GET['role-chosen'] : null;
    $result = isset($_GET['result']) ? $_GET['result'] : null;

    if ($idteam && $idmember && ($result == "approved" || $result == "rejected")) {
        $proposal = new Proposal();
        if ($result == "approved") {
            $prop1 = $proposal->updateAllOtherProposals($idmember, $idteam);
        }

        $prop2 = $proposal->updateProposalStatus($idmember, $idteam, $result);

        if ($prop2) {
            if ($result == "approved") {
                $proposal->addTeamMember($idteam, $idmember, $chosen_role);
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