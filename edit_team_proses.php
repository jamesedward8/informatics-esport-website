<?php
    require_once('teamClass.php');

    if (isset($_POST['btnEditEv'])) {
        $team = new Team();

        $data = [
            'idteam' => $_POST['idteam'],
            'idgame' => $_POST['idgame'],
            'name' => $_POST['name']
        ];

        if ($team->updateTeam($data) > 0) {
            echo "<script>
                    alert('Data updated successfully!');
                    window.location.href='team.php?idteam={$data['idteam']}&result=updated';
                </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to update game data.');
                    window.location.href='edit_team.php?idteam={$data['idteam']}';
                </script>";
        }
    }
?>
