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

$resultTotal = $mysqli->query("SELECT COUNT(*) AS total FROM team_members WHERE idmember = $iduser");
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
                $stmt_my_team = $mysqli->prepare("SELECT t.idteam, t.name 
                            FROM team_members tm 
                            JOIN team t ON tm.idteam = t.idteam 
                            JOIN join_proposal jp ON jp.idteam = tm.idteam AND jp.idmember = tm.idmember 
                            WHERE tm.idmember = ? AND jp.status = 'approved'");
                $stmt_my_team->bind_param("i", $iduser);
                $stmt_my_team->execute();
                $result_my_team = $stmt_my_team->get_result();

                if ($result_my_team->num_rows > 0) {
                    echo "<table class='tableEvent'>";
                    echo "<thead>";
                    echo "<tr><th>Team Name</th><th>Action</th></tr>";
                    echo "</thead><tbody>";

                    while ($row_my_team = $result_my_team->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row_my_team['name'] . "</td>";
                        echo "<td><a style='color:orange;' href='view_team_members.php?team-id=" . $row_my_team['idteam'] . "&team-name=" . urlencode($row_my_team['name']) . "'>View Members</a></td>";
                        echo "</tr>";
                    }

                    echo "</tbody></table>";
                } else {
                    echo "<p>You don't have access to any teams.</p>";
                }
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