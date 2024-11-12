<?php 
    require_once('teamClass.php');

    if (isset($_POST['btnAddEv'])) {
        $team = new Team();

        extract($_POST);
        $data = [
            'idgame' => $idgame,
            'name' => $name
        ];

        if ($team->addTeam($data) > 0) {
            echo "<script>
                    alert('Data added successfully!');
                    window.location.href='team.php?result=added';
                  </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to add team data.');
                    window.location.href='add_team.php';
                  </script>";
        }
    }
?>