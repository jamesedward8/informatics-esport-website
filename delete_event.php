<?php
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
} else {
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
    <title>Delete Event</title>
</head>

<body>
    <?php include 'header.php' ?>
    </header>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Editing Event</h1>
            </div>
            <div class="content-event">
                <?php
                if (isset($_GET['idevent'])) {
                    if ($_GET['idevent'] != null) {
                        $idevent = $_GET['idevent'];

                        $stmt = $mysqli->prepare("DELETE FROM event WHERE idevent = ?");
                        $stmt->bind_param("i", $idevent);
                        $stmt->execute();

                        $affected = $stmt->affected_rows;

                        echo "<script>
                                alert('Data deleted successfully!');
                                alert('" . $affected . " data updated');
                                window.location.href='event.php?idevent=$idevent&result=updated';
                            </script>";

                        $stmt->close();
                    }
                }

                $mysqli->close();
                ?>
            </div>
        </article>

        <?php
        $mysqli->close();
        ?>
    </main>
</body>

</html>