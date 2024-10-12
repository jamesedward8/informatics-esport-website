<?php 
    session_start();

    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

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
        <title>Join Proposal Management - Informatics Esports</title>
    </head>
    <body>
        <?php 
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Join Proposal Management</h1>
                </div><br><br><br>
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

                        if ($role == "admin") {
                            $stmt = $mysqli->prepare("SELECT t.idteam, t.name as team_name, g.name as game_name, m.username, jp.description, jp.status, jp.idmember
                            FROM join_proposal jp 
                            INNER JOIN team t ON jp.idteam = t.idteam 
                            INNER JOIN game g ON t.idgame = g.idgame 
                            INNER JOIN member m ON jp.idmember = m.idmember
                            LIMIT ? OFFSET ?");
                            $stmt->bind_param('ii', $limit, $offset);
                            $stmt->execute();
                            $result = $stmt->get_result();
                        } 

                        if ($result->num_rows == 0) {
                            echo "<tr><td colspan='5'>No join proposal found.</td></tr>";
                        } 
                        else {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                if ($role == "admin") {
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['team_name'] . "</td>";
                                    echo "<td>" . $row['game_name'] . "</td>";
                                    echo "<td>" . $row['description'] . "</td>";
                                    
                                    if ($row['status'] == 'waiting') {
                                        echo "<td colspan='1'>
                                                <a class='td-btn-edit' id='approve' href='join_team_result.php?idteam=". $row['idteam'] ."&idmember=". $row['idmember'] ."&role-chosen=". $row['description'] ."&result=approved' name='btn-acc'>Approve<a></td>
                                            <td colspan='1'>
                                                <a style='color: red;' class='td-btn-edit' id='reject' href='join_team_result.php?idteam=". $row['idteam'] ."&idmember=". $row['idmember'] ."&role-chosen=". $row['description'] ."&result=rejected' name='btn-rej'>Reject</a>
                                            </td>";
                                    } 
                                    else {
                                        if ($row['status'] == 'approved') {
                                            echo "<td colspan='2'><span style='color: green; font-weight: bold;'>Approved</span></td>";
                                        }
                                        elseif ($row['status'] == 'rejected') {
                                            echo "<td colspan='2'><span style='color: darkred; font-weight: bold;'>Rejected</span></td>";
                                        }
                                    }
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#approve').click(function(e) {
                var confirmation = confirm("Are you sure you want to approve?");
                
                if (!confirmation) {
                    e.preventDefault();     
                }
            });

            $('#reject').click(function(e) {
                var confirmation = confirm("Are you sure you want to reject?");
                
                if (!confirmation) {
                    e.preventDefault();     
                }
            });
        });
    </script>
</html>
