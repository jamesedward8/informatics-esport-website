<?php 
    session_start();

    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;
    $idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;
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
        <title>Team Proposal Submission - Informatics Esports</title>
    </head>
    <body>
        <?php 
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Join a Team</h1>
                </div>
                <div class="content-page">
                    <form action="join_team_process.php" method="POST">
                        <br><br><br>
                        <div class="mb-3">
                            <input type="hidden" name="idteam" value="<?php echo htmlspecialchars($idteam) ?>">
                            <label class="form-label, label-add-event">Role Preference in Game: </label>
                            <input type="text" name="role" class="form-control, input-add-event" required placeholder="supporter, attacker, defender, tanker, etc."> 
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <input type="submit" class="btn-add-team" name="btn-join" value="Submit" id="submit">
                        </div>
                    </form>
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
            $('#submit').click(function(e) {
                var confirmation = confirm("Are you sure you want to to join the team?");
                
                if (!confirmation) {
                    e.preventDefault();     
                }
            }); 
        });
    </script>
</html>