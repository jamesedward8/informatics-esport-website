<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Database connection succeed!";
    }

    $user = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Delete Achievement</title>
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
                <h1 class="h1-content-title">Deleting Achievement</h1>
            </div>
            <div class="content-event">     
            <?php
                if (isset($_GET['idachievement'])) {
                    if ($_GET['idachievement'] != null) {
                        $idachievement = $_GET['idachievement'];

                        $stmt = $mysqli->prepare("DELETE FROM achievement WHERE idachievement = ?");
                        $stmt->bind_param("i", $idachievement);
                        $stmt->execute();

                        $affected = $stmt->affected_rows;

                        echo "<script>
                                alert('Data deleted successfully!');
                                window.location.href='home.php?idachievement=$idachievement&result=updated';
                            </script>";

                        $stmt->close();
                    }
                }

                $mysqli->close();
            ?>
            </div>
        </article>

        <?php
            $mysqli->close();
        ?>
    </main>
</body>
</html>