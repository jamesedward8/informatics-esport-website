<?php 
    session_start();
    require_once('teamClass.php');

    $teamObj = new Team();
    $team = null;
    
    if (isset($_GET['idteam']) && $_GET['idteam'] != null) {
        $idteam = $_GET['idteam'];
        $team = $teamObj->getTeamById($idteam);
        if (!$team) {
            echo "<h1 style='color:red;'>Team does not exist.</h1>";
        }
    }

    $games = $teamObj->getAllGames();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:wght@300;400;700&display=swap" rel="stylesheet">
    <title>Edit Team - Informatics Esports</title>
</head>
<body>
    <?php include('header.php'); ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Edit Team</h1>
            </div>
            <div class="content-page">
                <?php if ($team): ?>
                    <form action="edit_team_proses.php" method="POST">
                        <input type="hidden" name="idteam" value="<?php echo $team['idteam'] ?>">
                        <div class="mb-3">
                            <label for="name" class="form-label label-edit-event">Team Name:</label>
                            <input type="text" class="form-control input-edit-event" name="name" id="name" value="<?php echo $team['name'] ?>" required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="idgame" class="form-label label-add-event">Game Name:</label>
                            <select name="idgame" id="idgame" required>
                                <option value="">Select Game</option>
                                <?php foreach ($games as $game): ?>
                                    <option value="<?php echo $game['idgame'] ?>" <?php echo ($game['idgame'] == $team['idgame']) ? 'selected' : '' ?>>
                                        <?php echo $game['name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <input type="submit" class="btn-edit-event" value="Save Changes" name="btnEditEv">
                        </div>      
                    </form>
                <?php endif;?>
            </div>
        </article>
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
            } else {
                e.preventDefault(); 
                alert('Please fill in all required fields.'); 
            }
        });
    });
</script>
</html>