<?php 
    session_start();
    require_once("gameClass.php");

    $game = null;
    if (isset($_GET['idgame']) && $_GET['idgame'] != null) {
        $idgame = $_GET['idgame'];
        
        $gameInstance = new Game();
        $game = $gameInstance->getGameById($idgame); 
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/main.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
        <title>Edit Game - Informatics Esports</title>
    </head>
    <body>
        <?php include('header.php'); ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Edit Game</h1>
                </div>
                <div class="content-page">     
                    <?php if ($game): ?>
                        <form action="edit_game_proses.php" method="POST">
                            <input type="hidden" name="idgame" value="<?php echo $game['idgame'] ?>">
                            <div class="mb-3">
                                <label for="name" class="form-label, label-edit-event">Game Name:</label>
                                <input type="text" class="form-control, input-edit-event" name="name" id="name" required value="<?php echo $game['name'] ?>">
                            </div>
                            <br><br>

                            <div class="mb-3">
                                <label for="desc" class="form-label, label-edit-event">Description:</label>
                                <textarea class="form-control, ta-edit-event" id="desc" name="desc" rows="5" required><?php echo $game['description'] ?></textarea>
                            </div>
                            <br><br>

                            <div class="mb-3">
                                <input type="submit" class="btn-edit-event" value="Save Changes" name="btnEditEv">
                            </div>      
                        </form>
                    <?php else: ?>
                        <h1 style="color:red;">Game does not exist.</h1>
                    <?php endif; ?>
                </div>
            </article>
        </main>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                if (this.checkValidity()) {
                    var confirmation = confirm("Are you sure you want to edit this game?");
                    if (!confirmation) {
                        e.preventDefault(); 
                    }
                } else {
                    e.preventDefault(); 
                    alert('Please fill in all required fields.'); 
                }
            });
        });
    </script>
</html>