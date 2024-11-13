<?php
    session_start();
    require_once('achievementClass.php');
    require_once('pagination.php');

    if (isset($_GET['login']) && $_GET['login'] == 'success') {
        echo "<script>
                window.onload = function() {
                    alert('Welcome, " . $_SESSION['username'] . "!');
                }
            </script>";
    }

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $iduser = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

    $limit = 3;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $pageAchieve = new Achievement();
    
    if ($role == "admin") {
        // Admin sees all achievements with pagination
        $totalData = $pageAchieve->getTotalAchievement();
        $resAch = $pageAchieve->getAchievements($offset, $limit);
    } else if ($role == "member") {
        // Member sees only achievements for their teams with pagination
        $totalData = $pageAchieve->getTotalAchievementsForMemberTeams($iduser);
        $resAch = $pageAchieve->getAchievementsForMemberTeams($iduser, $offset, $limit);
    } else {
        // No user logged in: display all achievements without action buttons
        $totalData = $pageAchieve->getTotalAchievement();
        $resAch = $pageAchieve->getAchievements($offset, $limit);
    }

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
        <?php include('header.php'); ?>

        <main class="content-home">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Achievements</h1>
                </div>
                
                <div class="side-content-event">
                    <?php if ($role == "admin"): ?>
                        <form action="add_achievement.php" method="POST">
                            <input type="submit" class="btn-add-ev" value="ADD" name="btnAdd">
                        </form>
                    <?php endif; ?>
                </div>
                
                <div class="content-page">
                    <?php
                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                    echo "<thead>";

                    echo "<tr>
                            <th>Achievement Name</th>
                            <th>Game Name</th>
                            <th>Team Name</th>
                            <th>Date</th>
                            <th>Description</th>";
                    
                    if ($role == "admin") {
                        echo "<th colspan=2>Action</th>";
                    }
                    
                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    if (empty($resAch)) {
                        echo "<tr>
                                <td colspan='6'>No Achievement Available, Stay Tuned!</td>
                              </tr>";
                    } else {
                        foreach ($resAch as $row) {
                            echo "<tr>
                                    <td>" . $row['achievement_name'] . "</td>
                                    <td>" . $row['game_name'] . "</td>
                                    <td>" . $row['team_name'] . "</td>
                                    <td>" . $row['date'] . "</td>
                                    <td>" . $row['description'] . "</td>";

                            if ($role == "admin") {
                                echo "<td><a class='td-btn-edit' href='edit_achievement.php?idachievement=" . $row['idachievement'] . "'>Edit</a></td>
                                      <td><a class='td-btn-delete' href='delete_achievement.php?idachievement=" . $row['idachievement'] . "'>Delete</a></td>";
                            }
                            
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

        <?php include('footer.php'); ?>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.td-btn-delete').click(function(e) {
                var confirmation = confirm("Are you sure you want to delete this achievement?");
                
                if (!confirmation) {
                    e.preventDefault();
                }
            });
        });
    </script>
</html>