<?php
    require_once("gameClass.php");

    if (isset($_POST['btnAddEv'])) {
        $game = new Game();

        extract($_POST);
        $data = [
            'name' => $name,
            'description' => $desc
        ];

        if ($game->addGame($data) > 0) {
            echo "<script>
                    alert('Data added successfully!');
                    window.location.href='game.php?result=added';
                </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to add game data.');
                    window.location.href='add_game.php';
                </script>";
        }
    }
?>