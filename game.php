<?php
    session_start(); 
    include('gameClass.php');
    require_once('pagination.php');
    
    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null; 

    $limit = 3; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
    $offset = ($page - 1) * $limit; 

    $pageEvent = new Game();
    $totalData = $pageEvent->getTotalGame();
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
        <title>Game - Informatics Esports</title>
    </head>
    <body>
        <?php  
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Game Dashboard</h1>
                </div>
                <div class="side-content-event">
                    <form action="add_game.php" method="POST">
                        <?php 
                            if ($role == "admin") {
                                echo "<input type='submit' class='btn-add-ev' value='ADD' name='btnAdd'>";
                            }
                        ?>
                    </form>
                </div>
                <div class="content-page">
                    <?php
                        $game = new Game();
                        $resGame = $game->getGame($offset,$limit);  

                        echo "<br><br>";
                        echo "<table class='tableEvent'>";
                        echo "<thead>";
                            if ($role == "admin") {
                                echo "<tr>
                                        <th>Game</th>     
                                        <th>Description</th>
                                        <th colspan=2>Action</th>
                                     </tr>";
                            } else {
                                echo "<tr>
                                        <th>Game</th>     
                                        <th>Description</th>
                                     </tr>";
                            }
                        echo "</thead>";
                        echo "<tbody>";

                        if ($resGame->num_rows == 0) {
                            echo "<tr>
                                    <td colspan='3'>No Game Available, Stay Tuned!</td>
                                 </tr>";
                        } 
                        else {
                            while ($row = $resGame->fetch_assoc()) {
                                if ($role == "admin") {
                                    echo "<tr>
                                            <td>" . $row['name'] . "</td>
                                            <td>" . $row['description'] . "</td>
                                            <td><a class='td-btn-edit' href='edit_game.php?idgame=". $row['idgame'] ."' style='display:".(($role=="admin")?"block":"none").";'>Edit</a></td>
                                            <td><a class='td-btn-delete' href='delete_game.php?idgame=". $row['idgame'] ."' style='display:".(($role=="admin")?"block":"none").";'>Delete</a></td>
                                         </tr>";  
                                } 
                                else {
                                    echo "<tr>
                                            <td>" . $row['name'] . "</td>
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
            include('footer.php');
        ?>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.td-btn-delete').click(function(e) {
                var confirmation = confirm("Are you sure you want to delete this game?");
                
                if (!confirmation) {
                    e.preventDefault();     
                }
            });
        });
    </script>
</html>