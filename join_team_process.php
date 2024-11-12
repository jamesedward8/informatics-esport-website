<?php 
    session_start();
    require_once('proposalClass.php');

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
            $proposal = new Proposal();
            $addProp = $proposal->addProposal($idmember, $idteam, $role_in_game); 
            if ($addProp) {
                echo "<script>alert('Your join proposal is being processed, please wait!'); window.location.href='team.php'; </script>";
            } 
            else {
                echo "<script>alert('Error: Unable to submit your proposal.'); window.history.back();</script>";
            }
        } 
        else {
            echo "<script>alert('Please complete all fields.'); window.history.back();</script>";
        }
    }
?>