<?php 
    require_once('teamClass.php');
    
    if (isset($_GET['idteam'])  && !empty($_GET['idteam'])) {
        $idteam = $_GET['idteam'];
        $team = new Team();

        if ($team->deleteTeam($idteam) > 0) {
            echo "<script>
                    alert('Data deleted successfully!');
                    window.location.href='team.php?&result=deleted';
                    </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Team could not be deleted.');
                    window.location.href='team.php';
                    </script>";
        }
    } 
    else {
        echo "<script>
                alert('Invalid Team ID.');
                window.location.href='team.php';
                </script>";
    }
?>