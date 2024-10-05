<?php 
session_start();

$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

$idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;

$limit = 3; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
$offset = ($page - 1) * $limit; 

$resultTotal = $mysqli->query("SELECT COUNT(*) AS total FROM join_proposal");
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
    <title>Join Proposal Status</title>
</head>
<body>
    <?php 
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Join Proposal Status</h1>
            </div>
            <br><br><br>
            <div class="content-page">
                <?php 
                    echo "<table class='tableEvent'>";
                        echo "<thead>";
                            echo "<tr>";
                                if ($role == "member") {
                                    echo "<th>Team Name</th>";
                                    echo "<th>Game</th>";
                                    echo "<th>Role</th>";
                                    echo "<th>Status</th>";
                                }
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        if ($role == "member") { 
                            // Query for members to fetch only their own proposal
                            $stmt = $mysqli->prepare("SELECT t.idteam, g.name, t.name, m.username, jp.description, jp.status FROM join_proposal jp INNER JOIN team t ON jp.idteam = t.idteam INNER JOIN game g ON t.idgame=g.idgame INNER JOIN member m ON jp.idmember = m.idmember WHERE jp.idmember = ?");
                            $stmt->bind_param('i', $idmember);
                        }
                        
                        $stmt->execute();
                        $result = $stmt->get_result();

                        // Loop through the results and display them
                        while ($row = $result->fetch_assoc()) {
                            $idteam = $row['idteam'];
                            $team = $mysqli->query("SELECT * FROM team WHERE idteam = '$idteam'")->fetch_assoc();
                            $game = $mysqli->query("SELECT * FROM game WHERE idgame = '$team[idgame]'")->fetch_assoc();
                            echo "<tr>";
                                if ($role == "member") {
                                    // Display only team name, game, role, and status for the member
                                    echo "<td>" . $team['name'] . "</td>";
                                    echo "<td>" . $game['name'] . "</td>";
                                    echo "<td>" . $row['description'] . "</td>";
                                    
                                    // Status with color coding
                                    if ($row['status'] == "waiting") {
                                        echo "<td style='color: lightblue; font-weight: bold;'>". $row['status'] ."</td>";
                                    } else if ($row['status'] == "approved") {
                                        echo "<td style='color: green; font-weight: bold;'>". $row['status'] ."</td>";
                                    } else if ($row['status'] == "rejected") {
                                        echo "<td style='color: red; font-weight: bold;'>". $row['status'] ."</td>";
                                    }
                                }
                            echo "</tr>";
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
