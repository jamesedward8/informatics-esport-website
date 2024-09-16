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
    <title>Team - Informatics Esports</title>
</head>
<body>
    <?php  
        include('header.php');
    ?>

    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Team Dashboard</h1>
            </div>
            <div class="side-content-event">
                <form action="add_team.php" method="POST">
                    <input type="submit" class="btn-add-ev" value="ADD" name="btnAdd">
                </form>
            </div>
            <div class="content-page">
                <?php
                    //! SELECT event query
                    $stmt = $mysqli->prepare("SELECT t.name as namateam, g.name as namagame, t.idteam FROM team t JOIN game g ON t.idgame = g.idgame");
                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                    echo "<thead>";
                    echo "<tr>
                            <th>Team Name</th>
                            <th>Game Name</th>
                            <th colspan=2>Action</th>
                        </tr>";
                    echo "</thead>";

                    echo "<tbody>";

                    if ($res->num_rows == 0) {
                        echo "<tr>
                                <td colspan='5'>No Team Available, Stay Tuned!</td>
                            </tr>";
                    }

                    else {
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['namateam'] . "</td>
                                    <td>" . $row['namagame'] . "</td>
                                    <td><a class='td-event-edit' href='edit_team.php?idteam=". $row['idteam'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Edit</a></td>
                                    <td><a class='td-event-delete' href='delete_team.php?idteam=". $row['idteam'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Delete</a></td>
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