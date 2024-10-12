<?php 
    session_start();
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
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
        <title>Add Team</title>
    </head>
    <body>
        <?php  
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Add Team</h1>
                </div>
                <div class="content-page">     
                    <form action="add_team_proses.php" method="POST">
                        <br><br><br><br><br><br>
                        <div class="mb-3">
                            <label for="name" class="form-label, label-add-event">Team Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" name="name" class="form-control, input-add-event" id="name" placeholder="Enter team name here..." required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="idgame" class="form-label, label-add-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <?php  
                                $stmt = $mysqli->prepare("SELECT * from game");
                                $stmt->execute();
                                $res = $stmt->get_result();
                            ?>

                            <select class="cmb-choose-game" name="idgame" id="idgame" required>
                                <option value="" disabled selected>Pilih Game</option>
                                <?php
                                    while($row = $res->fetch_assoc()) {
                                        echo "<option value=".$row['idgame'].">"
                                            .$row['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <br><br>
                        
                        <div class="mb-3">
                            <input type="submit" class="btn-add-team" value="ADD" name="btnAddEv">
                        </div>      
                    </form>
                </div>
            </article>
            <?php
                $mysqli->close();
            ?>
        </main>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                if (this.checkValidity()) {
                    var confirmation = confirm("Are you sure you want to add this team?");
                    if (!confirmation) {
                        e.preventDefault(); 
                    }
                } 
                else {
                    e.preventDefault(); 
                    alert('Please fill in all required fields.'); 
                }
            });
        });
    </script>
</html>

