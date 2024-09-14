<?php 
    $mysqli = new mysqli ("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Database connection succeed!";
        echo "<br>";
    }

    $user = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Edit Game</title>
</head>
<body>
    <header class="header">
        <div class="overlay" data-overlay></div>
        <div class="containerLogo">
            <img src="img/logo.png" alt="logo" class="logo">
        </div>
        <div class="nav">
            <div class="nav-kiri">
                <a href="home.php" <?php echo "style = 'display:".(($user=="admin")?"yes":"none")."';"?>><nav class="navbar">Home</nav></a>
                <a href="event.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Event</nav></a>
                <a href="game.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Division</nav></a>
                <a href="team.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Team</nav></a>
                <a href="recruitment.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Recruitment</nav></a>
                <a href="manage.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Manage</nav></a> 
            </div>
            <div class="nav-kanan">
                <button>Login</button>
            </div>
        </div>
    </header>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Editing Game</h1>
            </div>
            <div class="content-event">     
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
                <br><br><br><br><br><br><br><br><br><br><br><br>
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