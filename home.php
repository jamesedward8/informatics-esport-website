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
    <title>Home</title>
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
                <h1 class="h1-content-title">Our Achievement</h1>
            </div>
            <div class="side-content-event">
                <form action="add_achievement.php" method="POST">
                    <input type="submit" class="btn-add-ev" value="Add" name="btnAdd">
                </form> 
            </div>
            <div class="content-event">
                <?php
                    $stmt = $mysqli->prepare("SELECT a.name as achievement_name, t.name as team_name, a.date, a.description, g.idgame, g.name as game_name, a.idachievement FROM achievement a JOIN team t ON a.idteam = t.idteam JOIN game g ON t.idgame = g.idgame");
                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                    echo "<thead>";
                    echo "<tr>
                            <th>Achievement Name</th>
                            <th>Game Name</th>
                            <th>Team Name</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th colspan=2>Action</th>
                        </tr>";
                    echo "</thead>";

                    echo "<tbody>";

                    if ($res->num_rows == 0) {
                        echo "<tr>
                                <td colspan='6'>No Achievement Available, Stay Tuned!</td>
                            </tr>";
                    }

                    else {
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['achievement_name'] . "</td>
                                    <td>" . $row['game_name'] . "</td>
                                    <td>" . $row['team_name'] . "</td>
                                    <td>" . $row['date'] . "</td>
                                    <td>" . $row['description'] . "</td>
                                    <td><a class='td-event-edit' href='edit_achievement.php?idachievement=". $row['idachievement'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Edit</a></td>
                                    <td><a class='td-event-delete' href='delete_achievement.php?idachievement=". $row['idachievement'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Delete</a></td>
                                </tr>";  
                        }
                    }

                    echo "</tbody>";
                    echo "</table>";
                ?>
            </div>
        </article>
    </main>
</body>
</html>