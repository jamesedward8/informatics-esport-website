<?php
    session_start();
    require_once('proposalClass.php');
    require_once('pagination.php');

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $iduser = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;
    $view_team_id = isset($_GET['team-id']) ? $_GET['team-id'] : null;
    $view_team_name = isset($_GET['team-name']) ? $_GET['team-name'] : null;

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
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <title>Members of Team <?php echo $view_team_name ?> - Informatics Esports</title>
</head>

<body>
    <?php
    include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Members of Team <?php echo $view_team_name ?></h1>
            </div>
            <div class="content-page">
                <?php
                $team = new Proposal();
                $teamMembers = $team->viewTeamMembers($view_team_id);

                echo "<br><br>";
                echo "<table class='tableEvent'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Username</th>";
                echo "<th>Role</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                while ($row_team_members = $teamMembers->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . ($row_team_members['username'] == $_SESSION['username'] ? "<b>" . $row_team_members['username'] . " (saya)" . "</b>" : $row_team_members['username']) . "</td>";
                    echo "<td>" . $row_team_members['description'] . "</td>";
                    echo "</tr>";
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