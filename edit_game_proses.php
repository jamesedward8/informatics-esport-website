<?php 
    require_once("gameClass.php");

    if (isset($_POST['btnEditEv'])) {
        $game = new Game();

        $data = [
            'id' => $_POST['idgame'],
            'name' => $_POST['name'],
            'description' => $_POST['desc']
        ];

        if ($game->updateGame($data) > 0) {
            echo "<script>
                    alert('Data updated successfully!');
                    window.location.href='game.php?idgame={$data['id']}&result=updated';
                </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to update game data.');
                    window.location.href='edit_game.php?idgame={$data['id']}';
                </script>";
        }
    }
?>