<?php 
    session_start();
    require_once('proposalClass.php');
    require_once("Pagination.php");

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

    $joinProposal = new Proposal();
    $totalData = $joinProposal->getTotalProposals($idmember);
    $totalPages = ceil($totalData / $limit);
    $proposals = $joinProposal->getProposalsByMember($idmember, $offset, $limit);
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
        <title>Join Proposal Status - Informatics Esports</title>
    </head>
    <body>
        <?php 
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Join Proposal Status</h1>
                </div><br><br><br>
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
                            while ($row = $proposals->fetch_assoc()) {
                                echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['team_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['game_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                    
                                    $statusColor = match($row['status']) {
                                        "waiting" => "lightblue",
                                        "approved" => "green",
                                        "rejected" => "red",
                                        default => "black"
                                    };
                                    echo "<td style='color: $statusColor; font-weight: bold;'>" . htmlspecialchars($row['status']) . "</td>";
                                echo "</tr>";
                            }
                        }
                        echo "</tbody>";
                        echo "</table>";
                    ?>
                </div>
                <div class="pagination">
                    <?php
                        echo Pagination::createPaginationLinks($page, $totalPages);
                    ?>
                </div>
            </article>
        </main>
        <?php 
            include('footer.php');
        ?>
    </body>
</html>