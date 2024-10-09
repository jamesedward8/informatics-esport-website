<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "esport");

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
    <title>Add Event</title>
</head>

<body>
    <?php
    include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Adding Game</h1>
            </div>
            <div class="content-page">
                <form action="add_game_proses.php" method="POST">
                    <br><br><br><br><br>
                    <div class="mb-3">
                        <label for="name" class="form-label, label-add-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" name="name" class="form-control, input-add-event" id="name" placeholder="Enter game name here...">
                    </div>
                    <br><br>
                    <div class="mb-3">
                        <label for="desc" class="form-label, label-add-event">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <br>
                        <textarea class="form-control, ta-add-event" name="desc" id="desc" rows="5" placeholder="Enter game description here..."></textarea>
                    </div>
                    <br><br>
                    <div class="mb-3">
                        <input type="submit" class="btn-add-event" value="Add Game" name="btnAddEv">
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