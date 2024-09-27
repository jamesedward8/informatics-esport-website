<?php 
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$user = "admin";

// Pagination setup
$limit = 3; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get current page number
$offset = ($page - 1) * $limit; // Calculate offset for SQL query

// Get total number of teams
$resultTotal = $mysqli->query("SELECT COUNT(*) AS total FROM team");
$rowTotal = $resultTotal->fetch_assoc();
$totalData = $rowTotal['total'];
$totalPages = ceil($totalData / $limit); // Calculate total pages
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
                    <input type="submit" class="btn-add-ev" value="ADD" name="btnAdd">
                </form>
            </div>
            <div class="content-page">
                <?php
                    // SELECT team query with LIMIT and OFFSET
                    $stmt = $mysqli->prepare("SELECT t.name as namateam, g.name as namagame, t.idteam 
                                               FROM team t 
                                               JOIN game g ON t.idgame = g.idgame 
                                               LIMIT ? OFFSET ?");
                    $stmt->bind_param('ii', $limit, $offset); // Bind parameters
                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                    echo "<thead>";
                    echo "<tr>
                            <th>Team Name</th>
                            <th>Game Name</th>
                            <th colspan=2>Action</th>
                        </tr>";
                    echo "</thead>";

                    echo "<tbody>";

                    if ($res->num_rows == 0) {
                        echo "<tr>
                                <td colspan='5'>No Team Available, Stay Tuned!</td>
                            </tr>";
                    } else {
                        while ($row = $res->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . $row['namateam'] . "</td>
                                    <td>" . $row['namagame'] . "</td>
                                    <td><a class='td-event-edit' href='edit_team.php?idteam=". $row['idteam'] ."' style='display:".(($user=="admin")?"block":"none").";'>Edit</a></td>
                                    <td><a class='td-event-delete' href='delete_team.php?idteam=". $row['idteam'] ."' style='display:".(($user=="admin")?"block":"none").";'>Delete</a></td>
                                </tr>";  
                        }
                    }

                    echo "</tbody>";
                    echo "</table>";
                ?>
            </div>
            <div class="pagination">
                <?php
                // Loop to display page numbers
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo "<a href='?page=$i' class='page-btn " . (($i == $page) ? 'active' : '') . "'>$i &nbsp; &nbsp; </a>";
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
