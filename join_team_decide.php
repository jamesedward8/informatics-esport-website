<?php 
    session_start();
    require_once('proposalClass.php');
    require_once('pagination.php');

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

    $limit = 3; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    $pageEvent = new Proposal();
    $totalData = $pageEvent->getTotalProposal();
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
                            $proposal = new Proposal();
                            $resProposal = $proposal->getProposal($offset, $limit);
                        } 

                        if ($resProposal->num_rows == 0) {
                            echo "<tr><td colspan='5'>No join proposal found.</td></tr>";
                        } 
                        else {
                            while ($row = $resProposal->fetch_assoc()) {
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
                            echo Pagination::createPaginationLinks($page, $totalPages);
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
