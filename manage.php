<?php 
    session_start();
    require_once('eventClass.php');
    require_once('pagination.php');

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;    

    $limit = 3; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    $pageAchieve = new Event();
    $totalData = $pageAchieve->getTotalEvent();
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
        <title>Manage - Informatics Esports</title>
    </head>
    <body>
        <?php  
            include('header.php');
            if ($role == "admin") {
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Team Manager</h1>
                </div>
                <div class="content-page">
                    <?php
                        $event = new Event();
                        $resEvent = $event->getEvent($offset, $limit);

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
                            } 
                        echo "</thead>";
                        echo "<tbody>";

                        if ($resEvent->num_rows == 0) {
                            echo "<tr>
                                    <td colspan='4'>No Event Available, Stay Tuned!</td>
                                </tr>";
                        } else {
                            while ($row = $resEvent->fetch_assoc()) {
                                $date = new DateTime($row['date']);
                                $formatDate = $date->format('d F Y');
                                if ($role == "admin") {
                                    echo "<tr>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $formatDate . "</td>
                                            <td>" . $row['description'] . "</td>
                                            <td><a class='td-btn-edit' href='join_event.php?idevent=". $row['idevent'] ."' style='display:".(($role=="admin")?"block":"none")."'>Manage</a></td>
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
                        echo Pagination::createPaginationLinks($page, $totalPages);
                    ?>
                </div>
            </article>
        </main>
        <?php 
                if ($role == "member") {
                    include('chatbox_member.php');
                } else if ($role == "admin") {
                    include('chatbox_admin.php');
                }       
            ?>
        <?php  
            }
            else {
                echo "<main class='content'>
                        <article>";
                echo "<div class='content-title'>
                        <h1 class='h1-content-title'>!! RESTRICTED CONTENT !!</h1>
                    </div>";
                echo "</article>
                    </main>";
            }
            include('footer.php');
        ?>
    </body>
</html>