<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    } 
    $user = "admin";
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
    <title>Edit Achievement</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            function filterTeams() {
                var selectedGame = $('#idgame').val();

                $('#idteam option').each(function() {
                    var teamGame = $(this).data('game'); 
                    if (teamGame == selectedGame || !selectedGame) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                if ($('#idteam option:selected').data('game') != selectedGame) {
                    $('#idteam').val('');
                }
            }

            filterTeams();

            $('#idgame').change(function() {
                filterTeams();
            });
        });
    </script>
</head>
<body>
    <?php  
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Edit Achievement</h1>
            </div>
            <div class="content-page">     
                <?php
                    if (isset($_GET['idachievement']) && $_GET['idachievement'] != null) {
                        $idachievement = $_GET['idachievement'];
                        
                        $stmt = $mysqli->prepare("SELECT achievement.*, team.idgame FROM achievement 
                                                  JOIN team ON achievement.idteam = team.idteam 
                                                  WHERE achievement.idachievement = ?");
                        $stmt->bind_param("i", $idachievement);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $achievement = $res->fetch_assoc();
                    }
                ?>

                <form action="edit_achievement_proses.php" method="POST">
                    <input type="hidden" name="idachievement" value="<?php echo $achievement['idachievement'] ?>">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label, label-add-event">Achievement Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" name="name" class="form-control, input-add-event" id="name" value="<?php echo $achievement['name']; ?>" placeholder="Enter achievement here...">
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <label for="idgame" class="form-label, label-add-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

                        <select name="idgame" id="idgame">
                            <option value="">Pilih Game</option>
                            <?php  
                                $stmt = $mysqli->prepare("SELECT * from game");
                                $stmt->execute();
                                $res = $stmt->get_result();

                                while($row = $res->fetch_assoc()) {
                                    $selected = ($row['idgame'] == $achievement['idgame']) ? 'selected' : '';
                                    echo "<option value='".$row['idgame']."' $selected>".$row['name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <label for="idteam" class="form-label, label-add-event">Team Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <select name="idteam" id="idteam">
                            <option value="">Pilih Team</option>
                            <?php  
                                $stmt = $mysqli->prepare("SELECT * FROM team");
                                $stmt->execute();
                                $res = $stmt->get_result();

                                while($team = $res->fetch_assoc()) {
                                    $selected = ($team['idteam'] == $achievement['idteam']) ? 'selected' : '';
                                    echo "<option value='".$team['idteam']."' data-game='".$team['idgame']."' $selected>".$team['name']."</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <label for="date" class="form-label, label-add-event">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" name="date" class="form-control, input-add-event" id="date" value="<?php echo $achievement['date']; ?>" placeholder="Enter event date here (yyyy-MM-dd)...">
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <label for="desc" class="form-label, label-add-event">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <br>
                        <textarea class="form-control, ta-add-event" name="desc" id="desc" rows="5" placeholder="Enter event description here..."><?php echo $achievement['description']; ?></textarea>
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <input type="submit" class="btn-add-event" value="Save Changes" name="btnEditEv">
                    </div>      
                </form>
            </div>
        </article>

        <?php
            $mysqli->close();
        ?>
    </main>
</body>
</html>
