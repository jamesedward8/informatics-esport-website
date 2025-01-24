<?php 
    session_start();
    require_once('gameClass.php');
    require_once('teamClass.php');
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
        <title>Add Achievement - Informatics Esports</title>
    </head>
    <body>
        <?php  
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Add Acheivement</h1>
                </div>
                <div class="content-submenu-page">     
                    <form action="add_achievement_proses.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label, label-add-event">Achievement Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" name="name" class="form-control, input-add-event" id="name" placeholder="Enter achievement here..." required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="idgame" class="form-label, label-add-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <?php  
                                $game = new Game();
                                $resGame = $game->getGame();
                            ?>
                            <select name="idgame" id="idgame" required>
                                <option value="" disabled selected>Pilih Game</option>
                                <?php
                                    while($row = $resGame->fetch_assoc()) {
                                        echo "<option value=".$row['idgame'].">"
                                            .$row['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="idteam" class="form-label, label-add-event">Team Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <select name="idteam" id="idteam" required>
                                <option value="" disabled selected>Pilih Team</option>
                                <?php  
                                    $team = new Team();
                                    $resTeam = $team->getAllTeams();

                                    while($team = $resTeam->fetch_assoc()) {
                                        echo "<option value='".$team['idteam']."' data-game='".$team['idgame']."'>".$team['name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="date" class="form-label, label-add-event">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="date" name="date" class="form-control, input-add-event" id="date" required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="desc" class="form-label, label-add-event">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <br>
                            <textarea class="form-control, ta-add-event" name="description" id="desc" rows="5" placeholder="Enter event description here..." required></textarea>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <input type="submit" class="btn-add-event" id="confirmation" value="ADD" name="btnAddEv">
                        </div>      
                    </form>
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
    </body>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#idgame').change(function() {
                var selectedGame = $(this).val();

                $('#idteam option').each(function() {
                    var teamGame = $(this).data('game'); 
                    if (teamGame == selectedGame || !selectedGame) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                $('#idteam').val('');
            });

            $('#idteam').change(function() {
                var selectedTeamGame = $(this).find(':selected').data('game');

                $('#idgame').val(selectedTeamGame);
            });


            $('form').submit(function(e) {
                if (this.checkValidity()) {
                    var confirmation = confirm("Are you sure you want to add this achievement?");
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