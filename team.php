<?php 
session_start();
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null; 
$iduser = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

$limit = 3; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$resultTotal = $mysqli->query("SELECT COUNT(*) AS total FROM team");
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
                    <?php 
                        if ($role == "admin") {
                            echo "<input type='submit' class='btn-add-ev' value='ADD' name='btnAdd'>";
                        }
                    ?>
                </form>
                <form action="join_team_status.php">
                    <?php 
                        if ($role == "member") {
                            echo "<input type='submit' class='btn-add-event' name='btn-check' value='Proposal Status'>";
                        }
                    ?>
                </form>
            </div>
            <div class="content-page">
                <?php
                    $stmt = $mysqli->prepare("SELECT t.name as namateam, g.name as namagame, t.idteam 
                                               FROM team t 
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
                                        <th>Team Name</th>
                                        <th>Game Name</th>
                                        <th colspan=2>Action</th>
                                    </tr>";
                            } else {
                                echo "<tr>
                                        <th>Team Name</th>
                                        <th>Game Name</th>
                                        <th>Action</th>
                                    </tr>";
                            }
                        echo "</thead>";

                        echo "<tbody>";

                        if ($res->num_rows == 0) {
                            echo "<tr>
                                    <td colspan='5'>No Team Available, Stay Tuned!</td>
                                </tr>";
                        } else {
                            while ($row = $res->fetch_assoc()) {
                                if ($role == "admin") {
                                    echo "<tr>
                                            <td>" . $row['namateam'] . "</td>
                                            <td>" . $row['namagame'] . "</td>
                                            <td><a class='td-btn-edit' href='edit_team.php?idteam=". $row['idteam'] ."' style='display:".(($role=="admin")?"block":"none").";'>Edit</a></td>
                                            <td><a class='td-btn-delete' href='delete_team.php?idteam=". $row['idteam'] ."' style='display:".(($role=="admin")?"block":"none").";'>Delete</a></td>
                                        </tr>";  
                                } 
                                
                                else {
                                    echo "<tr>
                                            <td>" . $row['namateam'] . "</td>
                                            <td>" . $row['namagame'] . "</td>
                                            <td><a class='td-btn-edit' href='join_team.php?idteam=". $row['idteam'] ."&idmember=". $iduser ."' style='display:".(($role=="member")?"block":"none").";' name='btn-Join'>Join</a></td>
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
        include ('footer.php');
    ?>
</body>
</html>
