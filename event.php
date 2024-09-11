<?php 

// Membuat koneksi MySQL
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
    <title>Event</title>
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
                <h1 class="h1-content-title">Event Dashboard</h1>
            </div>
            <div class="side-content-event">
                <form action="add_event.php" method="POST">
                    <input type="submit" class="btn-add-ev" value="Add" name="btnAdd">
                </form>
            </div>
            <div class="content-event">
                <?php
                    //! SELECT event query
                    $stmt = $mysqli->prepare("SELECT * FROM event");
                    $stmt->execute();
                    $res = $stmt->get_result();

                    echo "<br><br>";

                    echo "<table class='tableEvent'>";
                    echo "<thead>";
                    echo "<tr>
                            <th>Number</th>     
                            <th>Event Name</th>
                            <th>Date</th>
                            <th>Description</th>
                            <th colspan=2>Action</th>
                        </tr>";
                    echo "</thead>";

                    echo "<tbody>";

                    if ($res->num_rows == 0) {
                        echo "<tr>
                                <td colspan='5'>No Event Available, Stay Tuned!</td>
                            </tr>";
                    }

                    else {
                        while ($row = $res->fetch_assoc()) {
                            $date = new DateTime($row['date']);
                            $formatDate = $date->format('d F Y');

                            echo "<tr>
                                    <td>" . $row['idevent'] . "</td>
                                    <td>" . $row['name'] . "</td>
                                    <td>" . $formatDate . "</td>
                                    <td>" . $row['description'] . "</td>
                                    <td><a class='td-event-edit' href='edit_event.php?idevent=". $row['idevent'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Edit</a></td>
                                    <td><a class='td-event-delete' href='delete_event.php?idevent=". $row['idevent'] ."' 'style = 'display:".(($user=="admin")?"yes":"none")."';>Delete</a></td>
                                </tr>";  
                        }
                    }

                    echo "</tbody>";

                    echo "</table>";
                ?>
            </div>
        </article>
    </main>
</body>
</html>