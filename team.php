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
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap"
        rel="stylesheet">
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

                    else {
                        $stmt_view_team = $mysqli->prepare("SELECT tm.idteam, tm.idmember, tm.description, t.name as namateam, g.name as namagame 
                                                            FROM team_members tm 
                                                            JOIN team t ON tm.idteam = t.idteam 
                                                            JOIN game g ON t.idgame = g.idgame 
                                                            WHERE tm.idmember = ?");

                        $stmt_view_team->bind_param('i', $iduser);
                        $stmt_view_team->execute();
                        $res_view_team = $stmt_view_team->get_result();

                        if ($row_view_team = $res_view_team->fetch_assoc()) {
                            echo "<a class='btn-view-my-team' href='view_my_team.php?idteam=" . $row_view_team['idteam'] . "'>View My Team</a>";
                        } 
                    }
                    ?>
                </form>
            </div>
            <div class="content-page">
                <?php
                // Query teams with games
                $stmt = $mysqli->prepare("SELECT t.name as namateam, g.name as namagame, t.idteam, g.idgame 
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
                } else if ($role == "member") {
                    echo "<tr>
                                        <th>Team Name</th>
                                        <th>Game Name</th>
                                        <th>Action</th>
                                    </tr>";
                } else if ($role == null) {
                    echo "<tr>
                                        <th>Team Name</th>
                                        <th>Game Name</th>
                                    </tr>";
                }
                echo "</thead>";

                echo "<tbody>";

                if ($res->num_rows == 0) {
                    echo "<tr>
                                    <td colspan='5'>No Team Available, Stay Tuned!</td>
                                </tr>";
                } else {
                    // Check if the member is already part of a team for a specific game
                    $stmt_check_game = $mysqli->prepare("SELECT jp.idteam, t.idgame 
                                                                 FROM join_proposal jp 
                                                                 JOIN team t ON jp.idteam = t.idteam 
                                                                 WHERE jp.idmember = ? AND jp.status = 'approved'");
                    $stmt_check_game->bind_param('i', $iduser);
                    $stmt_check_game->execute();
                    $res_game_check = $stmt_check_game->get_result();

                    $accepted_games = [];
                    while ($game_row = $res_game_check->fetch_assoc()) {
                        $accepted_games[$game_row['idgame']] = $game_row['idteam'];
                    }

                    while ($row = $res->fetch_assoc()) {
                        if ($role == "admin") {
                            echo "<tr>
                                            <td>" . $row['namateam'] . "</td>
                                            <td>" . $row['namagame'] . "</td>
                                            <td><a class='td-btn-edit' href='edit_team.php?idteam=" . $row['idteam'] . "' style='display:" . (($role == "admin") ? "block" : "none") . ";'>Edit</a></td>
                                            <td><a class='td-btn-delete' href='delete_team.php?idteam=" . $row['idteam'] . "' style='display:" . (($role == "admin") ? "block" : "none") . ";'>Delete</a></td>
                                        </tr>";
                        } else if ($role == "member") {
                            echo "<tr>
                                            <td>" . $row['namateam'] . "</td>
                                            <td>" . $row['namagame'] . "</td>";

                            // Query 1: Check if a proposal is in waiting state for this team
                            $stmt1 = $mysqli->prepare("SELECT jp.idteam, jp.idmember 
                                                                       FROM join_proposal jp 
                                                                       WHERE jp.idteam = ? AND jp.status = 'waiting' AND jp.idmember = ?");
                            $stmt1->bind_param('ii', $row['idteam'], $iduser);
                            $stmt1->execute();
                            $res_jp_check = $stmt1->get_result();

                            if ($res_jp_check->num_rows > 0) {
                                // Proposal is in waiting state
                                echo "<td><a class='td-btn-edit' href='join_team_status.php?idteam=" . $row['idteam'] . "&idmember=" . $iduser . "' style='display:" . (($role == "member") ? "block" : "none") . ";'>See Status</a></td>";
                            } else {
                                // Query 2: Check if the proposal is accepted or rejected
                                $stmt2 = $mysqli->prepare("SELECT jp.status 
                                                                           FROM join_proposal jp 
                                                                           WHERE jp.idteam = ? AND jp.idmember = ?");
                                $stmt2->bind_param('ii', $row['idteam'], $iduser);
                                $stmt2->execute();
                                $res_status_check = $stmt2->get_result();

                                if ($res_status_check->num_rows > 0) {
                                    $proposal = $res_status_check->fetch_assoc();

                                    if ($proposal['status'] == 'approved') {
                                        // Proposal is approved
                                        echo "<td><span style='color: green; font-weight: bold;'>Approved</span></td>";
                                    } else if ($proposal['status'] == 'rejected') {
                                        // Proposal is rejected
                                        echo "<td><span style='color: red; font-weight: bold;'>Rejected</span></td>";
                                    } else {
                                        // If no specific proposal is found, check if the member is already in another team playing the same game
                                        if (isset($accepted_games[$row['idgame']])) {
                                            // Member is already accepted in another team for the same game
                                            echo "<td><span style='color: red; font-weight: bold;'>Not Eligible to Join</span></td>";
                                        } else {
                                            // Member is eligible to join this team
                                            echo "<td><a class='td-btn-edit' href='join_team.php?idteam=" . $row['idteam'] . "&idmember=" . $iduser . "' style='display:" . (($role == "member") ? "block" : "none") . ";' name='btn-Join'>Join</a></td>";
                                        }
                                    }
                                } else {
                                    // No proposal exists, but check if the member is already in another team for this game
                                    if (isset($accepted_games[$row['idgame']])) {
                                        // Member is already accepted in another team for the same game
                                        echo "<td><span style='color: darkred; font-weight: bold;'>Not Eligible to Join</span></td>";
                                    } else {
                                        // Member is eligible to join this team
                                        echo "<td><a class='td-btn-edit' href='join_team.php?idteam=" . $row['idteam'] . "&idmember=" . $iduser . "' style='display:" . (($role == "member") ? "block" : "none") . ";' name='btn-Join'>Join</a></td>";
                                    }
                                }
                            }

                            echo "</tr>";
                        } else if ($role == null) {
                            echo "<tr>
                                            <td>" . $row['namateam'] . "</td>
                                            <td>" . $row['namagame'] . "</td>
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
                if ($page > 1) {
                    echo "<a href='?page=1' class='page-btn'>First</a>";
                }
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' class='page-btn " . (($i == $page) ? 'active' : '') . "'>$i</a>";
                }
                if ($page < $totalPages) {
                    echo "<a href='?page=$totalPages' class='page-btn'>Last</a>";
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