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
    <title>Game - Informatics Esports</title>
</head>
<body>
    <?php  
        include('header.php');
    ?>

    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Game Dashboard</h1>
            </div>
            <div class="side-content-event">
                <form action="add_game.php" method="POST">
                    <input type="submit" class="btn-add-ev" value="ADD" name="btnAdd">
                </form>
            </div>
            <div class="content-page">
                <?php
                    $stmt = $mysqli->prepare("SELECT * FROM game");
                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                    echo "<thead>";
                    echo "<tr>
                            <th>Game</th>     
                            <th>Description</th>
                            <th colspan=2>Action</th>
                        </tr>";
                    echo "</thead>";

                    echo "<tbody>";

                    if ($res->num_rows == 0) {
                        echo "<tr>
                                <td colspan='3'>No Game Available, Stay Tuned!</td>
                            </tr>";
                    }

                    else {
                        while ($row = $res->fetch_assoc()) {

                            echo "<tr>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $row['description'] . "</td>
                                    <td><a class='td-event-edit' href='edit_game.php?idgame=". $row['idgame'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Edit</a></td>
                                    <td><a class='td-event-delete' href='delete_game.php?idgame=". $row['idgame'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Delete</a></td>
                                </tr>";  
                        }
                    }

                    echo "</tbody>";

                    echo "</table>";
                ?>
            </div>
        </article>
    </main>
    <?php  
        include ('footer.php');
    ?>
</body>
</html>