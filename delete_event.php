<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
</head>
<body>
<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Koneksi database berhasil!";
    }

    if (isset($_GET['idevent'])) {
        if ($_GET['idevent'] != null) {
            $idevent = $_GET['idevent'];

            $stmt = $mysqli->prepare("DELETE FROM event WHERE idevent = ?");
            $stmt->bind_param("i", $idevent);
            $stmt->execute();

            $affected = $stmt->affected_rows;

            echo "<br><br>";
            echo "Data deleted successfully!";
            echo "<br><br>";
            echo "<a href='event.php'>Back to Event</a>";

            $stmt->close();
        }
    }

    $mysqli->close();
?>
</body>
</html>