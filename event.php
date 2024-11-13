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
        // Fetch all events for admin
        $totalData = $pageEvent->getTotalEvent();
        $resEvent = $pageEvent->getEvent($offset, $limit);
    } else if ($role == "member") {
        // Fetch events only for the teams the member has joined
        $resEvent = $pageEvent->getEventForJoinedTeam($iduser); // This should fetch all joined team events at once
        $totalData = count($resEvent); // Count the events since we're not using pagination here for simplicity
    } else {
        $resEvent = [];
        $totalData = 0;
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
                    <form action="add_event.php" method="POST">
                        <?php 
                            if ($role == "admin") {
                                echo "<input type='submit' class='btn-add-ev' value='ADD' name='btnAdd'>";
                            }
                        ?>
                    </form>
                </div>
                
                <div class="content-page">
                    <?php
                        echo "<br><br>";
                        echo "<table class='tableEvent'>";
                        echo "<thead>";
                        
                        if ($role == "admin") {
                            echo "<tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th colspan=2>Action</th>
                                  </tr>";
                        } else {
                            echo "<tr>
                                    <th>Event Name</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                  </tr>";
                        }

                        echo "</thead>";
                        echo "<tbody>";

                        if (empty($resEvent)) {
                            echo "<tr>
                                    <td colspan='4'>No Event Available, Stay Tuned!</td>
                                  </tr>";
                        } else {
                            // Loop through fetched events based on role
                            foreach ($resEvent as $row) {
                                $date = new DateTime($row['date']);
                                $formatDate = $date->format('d F Y');

                                if ($role == "admin") {
                                    echo "<tr>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $formatDate . "</td>
                                            <td>" . $row['description'] . "</td>
                                            <td><a class='td-btn-edit' href='edit_event.php?idevent=" . $row['idevent'] . "'>Edit</a></td>
                                            <td><a class='td-btn-delete' href='delete_event.php?idevent=" . $row['idevent'] . "'>Delete</a></td>
                                          </tr>";
                                } else if ($role == "member") {
                                    echo "<tr>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $formatDate . "</td>
                                            <td>" . $row['description'] . "</td>
                                          </tr>";
                                }
                            }
                        }

                        echo "</tbody>";
                        echo "</table>";
                    ?>
                </div>
                
                <div class="pagination">
                    <?php
                        // Display pagination only if the user is an admin (pagination only implemented for full list)
                        if ($role == "admin") {
                            echo Pagination::createPaginationLinks($page, $totalPages);
                        }
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