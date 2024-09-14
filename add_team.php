<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Database connection succeed!";
    }

    $user = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Add Team</title>
</head>
<body>
    <header class="header">
        <div class="overlay" data-overlay></div>
        <div class="containerLogo">
            <img src="img/logo.png" alt="logo" class="logo">
        </div>
        <div class="nav">
            <div class="nav-kiri">
                <a href="home.php" <?php echo "style = 'display:".(($user=="admin")?"yes":"none")."';"?>><nav class="navbar">Home</nav></a>
                <a href="event.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Event</nav></a>
                <a href="game.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Division</nav></a>
                <a href="team.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Team</nav></a>
                <a href="recruitment.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Recruitment</nav></a>
                <a href="manage.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Manage</nav></a>  
            </div>
            <div class="nav-kanan">
                <button>Login</button>
            </div>
        </div>
    </header>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Adding Team</h1>
            </div>
            <div class="content-event">     
                <form action="add_team_proses.php" method="POST">
                    <br><br><br><br><br><br><br><br><br><br><br><br>
                    <div class="mb-3">
                        <label for="name" class="form-label, label-add-event">Team Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" name="name" class="form-control, input-add-event" id="name" placeholder="Enter team name here...">
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <label for="idgame" class="form-label, label-add-event">Game Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>

                        <?php  
                                $stmt = $mysqli->prepare("SELECT * from game");
                                $stmt->execute();
                                $res = $stmt->get_result();
                                ?>
                        <select name="idgame" id="idgame">
                        <option value="">Pilih Game</option>
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
                        <input type="submit" class="btn-add-event" value="Add Event" name="btnAddEv">
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

