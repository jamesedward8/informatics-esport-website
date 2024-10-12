<?php 
session_start();
    $mysqli = new mysqli ("localhost", "root", "", "esport");

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
        <title>Edit Team - Informatics Esports</title>
    </head>
    <body>
        <?php  
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Edit Team</h1>
                </div>
                <div class="content-page">     
                    <?php
                        if (isset($_GET['idteam']) && $_GET['idteam'] != null) {
                            $game = array();
                            $idteam = $_GET['idteam'];
                            
                            $stmt = $mysqli->prepare("SELECT * FROM team WHERE idteam = ?");
                            $stmt->bind_param("i", $idteam);
                            $stmt->execute();
                            $res = $stmt->get_result();
                            $team = $res->fetch_assoc();
                            $game[] = $team["idgame"]; 
                        }
                    ?>

                    <form action="edit_team_proses.php" method="POST">
                        <input type="hidden" name="idteam" value="<?php echo $team['idteam'] ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label, label-edit-event">Team Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" class="form-control, input-edit-event" name="name" id="name" value="<?php echo $team['name'] ?>" required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="idgame" class="form-label, label-add-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <select name="idgame" id="idgame" required>
                                <option value="">Pilih Game</option>
                                <?php
                                    $stmt = $mysqli->prepare("SELECT * FROM game");
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    while ($row = $res->fetch_assoc()) {
                                        $selected = ($row['idgame'] == $team['idgame']) ? 'selected' : '';
                                        echo "<option value='{$row['idgame']}' {$selected}>{$row['name']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <input type="submit" class="btn-edit-event" value="Save Changes" name="btnEditEv">
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
                    var confirmation = confirm("Are you sure you want to edit this team?");
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