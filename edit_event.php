<?php 
    $mysqli = new mysqli ("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Database connection succeed!";
        echo "<br>";
    }

    $user = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <title>Edit Event</title>
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
                <nav class="navbar">Division</nav>  
                <nav class="navbar">Ya Team</nav>
                <nav class="navbar">Recruitment</nav>
                <nav class="navbar">Manage </nav>  
            </div>
            <div class="nav-kanan">
                <button>Login</button>
            </div>
        </div>
    </header>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Editing Event</h1>
            </div>
            <div class="content-submenu-event">     
            <?php
                if (isset($_GET['idevent'])) {
                    if ($_GET['idevent'] != null) {
                        $idevent = $_GET['idevent'];
                        $stmt = $mysqli->prepare("SELECT * FROM event WHERE idevent = ?");
                        $stmt->bind_param("i", $idevent);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        $event = $res->fetch_assoc();
                    }
                }
            ?>

            <form action="edit_event_proses.php" method="POST">
                <input type="hidden" name="idevent" value="<?php echo $event['idevent'] ?>">
                <div class="mb-3">
                    <label for="name" class="form-label, label-edit-event">Event Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" class="form-control, input-edit-event" name="name" id="name" value="<?php echo $event['name'] ?>">
                </div>
                <br><br>
                <div class="mb-3">
                    <label for="date" class="form-label, label-edit-event">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input type="text" class="form-control, input-edit-event" name="date" id="date" value="<?php echo $event['date'] ?>">
                </div>
                <br><br>
                <div class="mb-3">
                    <label for="desc" class="form-label, label-edit-event">Description:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <br>
                    <textarea class="form-control, ta-edit-event" id="desc" name="desc" rows="5"><?php echo $event['description'] ?></textarea>
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
</html>