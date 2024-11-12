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

$view_team_id = isset($_GET['team-id']) ? $_GET['team-id'] : null;
$view_team_name = isset($_GET['team-name']) ? $_GET['team-name'] : null;

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
                $stmt_team_members = $mysqli->prepare("SELECT m.username, tm.description 
                        FROM team_members tm 
                        JOIN member m ON tm.idmember = m.idmember 
                        JOIN team t ON tm.idteam = t.idteam 
                        WHERE t.idteam = ?");

                $stmt_team_members->bind_param("s", $view_team_id);
                $stmt_team_members->execute();
                $result_team_members = $stmt_team_members->get_result();

                echo "<br><br>";
                echo "<table class='tableEvent'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th>Username</th>";
                echo "<th>Role</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                while ($row_team_members = $result_team_members->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row_team_members['username'] . "</td>";
                    echo "<td>" . $row_team_members['description'] . "</td>";
                    echo "</tr>";
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