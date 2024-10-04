<?php
session_start();  
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null;

$limit = 3; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$resultTotal = $mysqli->query("SELECT COUNT(*) AS total FROM achievement");
$rowTotal = $resultTotal->fetch_assoc();
$totalData = $rowTotal['total'];
$totalPages = ceil($totalData / $limit);
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
    <title>Home - Informatics Esports</title>
</head>
<body>
    <?php  
        include('header.php');
    ?>

    <div class="video-container">
        <video width="100%" height="auto" autoplay loop muted>
            <source src="img/VidHome1.mp4" type="video/mp4">
        </video>
    </div>

    <main class="content-home">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Achievements</h1>
            </div>
            <div class="side-content-event">
                <form action="add_achievement.php" method="POST">
                    <?php
                        if ($role == "admin") {
                            echo "<input type='submit' class='btn-add-ev' value='ADD' name='btnAdd'>";
                        }
                    ?>
                </form> 
            </div>
            <div class="content-page">
                <?php
                    $stmt = $mysqli->prepare("SELECT a.name as achievement_name, t.name as team_name, a.date, a.description, g.idgame, g.name as game_name, a.idachievement 
                                               FROM achievement a 
                                               JOIN team t ON a.idteam = t.idteam 
                                               JOIN game g ON t.idgame = g.idgame 
                                               LIMIT ? OFFSET ?");
                    $stmt->bind_param('ii', $limit, $offset); 
                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                        echo "<thead>";

                        if ($role == "admin") {
                            echo "<tr>
                                    <th>Achievement Name</th>
                                    <th>Game Name</th>
                                    <th>Team Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th colspan=2>Action</th>
                                </tr>";
                        } else {
                            echo "<tr>
                                    <th>Achievement Name</th>
                                    <th>Game Name</th>
                                    <th>Team Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                </tr>";
                        }
                    
                        echo "</thead>";

                        echo "<tbody>";

                    if ($res->num_rows == 0) {
                            echo "<tr>
                                    <td colspan='6'>No Achievement Available, Stay Tuned!</td>
                                </tr>";
                    } else {
                        while ($row = $res->fetch_assoc()) {

                                if ($role == "admin") {
                                    echo "<tr>
                                            <td>" . $row['achievement_name'] . "</td>
                                            <td>" . $row['game_name'] . "</td>
                                            <td>" . $row['team_name'] . "</td>
                                            <td>" . $row['date'] . "</td>
                                            <td>" . $row['description'] . "</td>
                                            <td><a class='td-btn-edit' href='edit_achievement.php?idachievement=". $row['idachievement'] ."' style='display:".(($role=="admin")?"block":"none").";'>Edit</a></td>
                                            <td><a class='td-btn-delete' href='delete_achievement.php?idachievement=". $row['idachievement'] ."' style='display:".(($role=="admin")?"block":"none").";'>Delete</a></td>
                                        </tr>";  
                                } else {
                                    echo "<tr>
                                            <td>" . $row['achievement_name'] . "</td>
                                            <td>" . $row['game_name'] . "</td>
                                            <td>" . $row['team_name'] . "</td>
                                            <td>" . $row['date'] . "</td>
                                            <td>" . $row['description'] . "</td>
                                        </tr>";
                                }
                            }
                        }
                        echo "</tbody>";
                    echo "</table>";
                ?>
            </div>

            <div class="pagination">
                <?php
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' class='page-btn " . (($i == $page) ? 'active' : '') . "'>$i</a>";
                }
                ?>
            </div>
            
        </article>
    </main>

    <?php  
        include('footer.php');
    ?>
</body>
</html>
