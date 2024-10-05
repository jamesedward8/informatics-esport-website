<?php 
session_start();

$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

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
    <title>Join Proposal Management</title>
</head>
<body>
    <?php 
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Join Proposal Management</h1>
            </div>
            <br><br><br>
            <div class="content-page">
                <?php 
                    echo "<table class='tableEvent'>";
                        echo "<thead>";
                            echo "<tr>";
                                if ($role == "admin") {
                                    echo "<th>Username</th>";
                                    echo "<th>Team Name</th>";
                                    echo "<th>Game</th>";
                                    echo "<th>Role</th>";
                                    echo "<th colspan='2'>Action</th>";
                                } 
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        // Query for admin to fetch all proposals for the team
                        if ($role == "admin") {
                            $stmt = $mysqli->prepare("SELECT t.idteam, t.name as team_name, g.name as game_name, m.username, jp.description, jp.status, jp.idmember
                            FROM join_proposal jp 
                            INNER JOIN team t ON jp.idteam = t.idteam 
                            INNER JOIN game g ON t.idgame = g.idgame 
                            INNER JOIN member m ON jp.idmember = m.idmember");
                        } 
                                
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows == 0) {
                            echo "<tr><td colspan='6'>No join proposal found.</td></tr>";
                        }

                        else {
                            // Loop through the results and display them
                            while ($row = $result->fetch_assoc()) {
                                  echo "<tr>";
                                    if ($role == "admin") {
                                        // Display the member's username, team name, game, and action options
                                        echo "<td>" . $row['username'] . "</td>";
                                        echo "<td>" . $row['team_name'] . "</td>";
                                        echo "<td>" . $row['game_name'] . "</td>";
                                        echo "<td>" . $row['description'] . "</td>";
                                        echo "<td><a class='td-btn-edit' href='join_team_result.php?idteam=". $row['idteam'] ."&idmember=". $row['idmember'] ."&result=approved' name='btn-acc'>Approve</a></td>";
                                        echo "<td><a style='color: red;' class= 'td-btn-edit' href='join_team_result.php?idteam=". $row['idteam'] ."&idmember=". $row['idmember'] ."&result=rejected' name='btn-rej'>Reject</a></td>";
                                    }           
                                echo "</tr>";
                            }
                        }
                        echo "</tbody>";
                    echo "</table>";
                ?>
            </div>
        </article>
    </main>
    <?php 
        include('footer.php');
    ?>
</body>
</html>
