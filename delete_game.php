<?php
  require_once("gameClass.php");

  if (isset($_GET['idgame']) && !empty($_GET['idgame'])) {
      $idgame = $_GET['idgame'];
      $game = new Game();

      if ($game->deleteGame($idgame) > 0) {
        echo "<script>
                alert('Data deleted successfully!');
                window.location.href='game.php?result=deleted';
              </script>";
      } 
      else {
        echo "<script>
                alert('Error: Game could not be deleted.');
                window.location.href='game.php';
              </script>";
      }
  } 
  else {
    echo "<script>
            alert('Invalid Game ID.');
            window.location.href='game.php';
          </script>";
  }
?>