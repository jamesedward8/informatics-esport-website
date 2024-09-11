<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php 

    // Membuat koneksi MySQL
    $mysqli = new mysqli ("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Koneksi database berhasil!";
        echo "<br>";
    }

    if (isset($_GET['idevent'])) {
        if ($_GET['idevent'] != null) {
            $idevent = $_GET['idevent'];
            $stmt = $mysqli->prepare("SELECT * FROM event WHERE idevent = ?");
            $stmt->bind_param("i", $idevent);
            $stmt->execute();
            $res = $stmt->get_result();
            $event = $res->fetch_assoc();

            $mysqli->close();
        }
    }
?>

    <form action="edit_event_proses.php" method="POST">
        <br><br>
        <input type="hidden" name="idevent" value="<?php echo $event['idevent'] ?>">
        <label for="name">Event Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $event['name'] ?>">
        <br><br>
        <label for="date">Date:</label>
        <input type="text" id="date" name="date" value="<?php echo $event['date'] ?>">
        <br><br>
        <label for="desc">Description:</label>
        <textarea id="desc" name="desc"><?php echo $event['description'] ?></textarea>
        <br><br>
        <input type="submit" value="Save Changes" name="btnEdit">
    </form>
</body>
</html>