<?php
    session_start();
    require_once('proposalClass.php');
    require_once('pagination.php');

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $iduser = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

    $limit = 3;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $pageTeamMembers = new Proposal();
    $totalData = $pageTeamMembers->getTotalTeamMembers($iduser);
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
        <title>View Team</title>
    </head>

    <body>
        <?php
        include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">My Team</h1>
                </div>
                <div class="content-page">
                    <?php
                    $team = new Proposal();
                    $viewTeam = $team->viewTeam($iduser);

                    if ($viewTeam->num_rows > 0) {
                        echo "<table class='tableEvent'>";
                        echo "<thead>";
                        echo "<tr><th>Team Name</th><th>Action</th></tr>";
                        echo "</thead><tbody>";

                        while ($row_my_team = $viewTeam->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row_my_team['name'] . "</td>";
                            echo "<td><a style='color:orange;' href='view_team_members.php?team-id=" . $row_my_team['idteam'] . "&team-name=" . urlencode($row_my_team['name']) . "'>View Members</a></td>";
                            echo "</tr>";
                        }

                        echo "</tbody></table>";
                    } 
                    else {
                        echo "<p>You don't have access to any teams.</p>";
                    }
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