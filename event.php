<?php
    session_start();
    require_once("eventClass.php");
    require_once('pagination.php');

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null; 
    $iduser = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

    $limit = 3;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $pageEvent = new Event();
    
    if ($role == "admin") {
        // Admin sees all events with pagination
        $totalData = $pageEvent->getTotalEvent();
        $resEvent = $pageEvent->getEvent($offset, $limit);
    } else if ($role == "member") {
        // Member sees only events for the teams they have joined with pagination
        $totalData = $pageEvent->getTotalEventsForMemberTeams($iduser);
        $resEvent = $pageEvent->getEventForJoinedTeam($iduser, $offset, $limit);
    } else {
        // No user logged in: display all events without action buttons
        $totalData = $pageEvent->getTotalEvent();
        $resEvent = $pageEvent->getEvent($offset, $limit);
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
        <title>Event - Informatics Esports</title>
    </head>

    <body>
        <?php include('header.php'); ?>
        
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Event Dashboard</h1>
                </div>
                
                <div class="side-content-event">
                    <?php if ($role == "admin"): ?>
                        <form action="add_event.php" method="POST">
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
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Description</th>";
                    
                    if ($role == "admin") {
                        echo "<th colspan=2>Action</th>";
                    }

                    echo "</tr>";
                    echo "</thead>";
                    echo "<tbody>";

                    if ($resEvent->num_rows == 0) {    
                        echo "<tr>
                                <td colspan='4'>No Event Available, Stay Tuned!</td>
                              </tr>";
                    } else {
                        foreach ($resEvent as $row) {
                            $date = new DateTime($row['date']);
                            $formatDate = $date->format('d F Y');

                            echo "<tr>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $formatDate . "</td>
                                    <td>" . $row['description'] . "</td>";

                            if ($role == "admin") {
                                echo "<td><a class='td-btn-edit' href='edit_event.php?idevent=" . $row['idevent'] . "'>Edit</a></td>
                                      <td><a class='td-btn-delete' href='delete_event.php?idevent=" . $row['idevent'] . "'>Delete</a></td>";
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
                var confirmation = confirm("Are you sure you want to delete this event?");
                
                if (!confirmation) {
                    e.preventDefault();     
                }
            });
        });
    </script>
</html>