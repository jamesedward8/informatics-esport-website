<?php 
    $mysqli = new mysqli ("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }


    $user = "admin";
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
    <title>Edit Game</title>
</head>
<body>
    <?php  
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Editing Game</h1>
            </div>
            <div class="content-page">     
            <?php
                if (isset($_GET['idgame'])) {
                    if ($_GET['idgame'] != null) {
                        $idgame = $_GET['idgame'];
                        $stmt = $mysqli->prepare("SELECT * FROM game WHERE idgame = ?");
                        $stmt->bind_param("i", $idgame);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $game = $res->fetch_assoc();
                    }
                }
            ?>

            <form action="edit_game_proses.php" method="POST">
                <input type="hidden" name="idgame" value="<?php echo $game['idgame'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label, label-edit-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" class="form-control, input-edit-event" name="name" id="name" value="<?php echo $game['name'] ?>">
                </div>
                <br><br>
                <div class="mb-3">
                    <label for="desc" class="form-label, label-edit-event">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <br>
                    <textarea class="form-control, ta-edit-event" id="desc" name="desc" rows="5"><?php echo $game['description'] ?></textarea>
                </div>
                <br><br>
                <div class="mb-3">
                    <input type="submit" class="btn-edit-event" value="Save Changes" name="btnEditEv">
                </div>      
            </form>
            </div>
        </article>

        <?php
            $mysqli->close();
        ?>
    </main>
</body>
</html>