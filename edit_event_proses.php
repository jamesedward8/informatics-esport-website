<?php 

    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    else {
        echo "Koneksi database berhasil!";
    }

    if (isset($_POST['btnEdit'])) {
        extract($_POST);
        $idevent = $_POST['idevent'];

        $stmt = $mysqli->prepare("UPDATE event SET name = ?, date = ?, description = ? WHERE idevent = ?");
        $stmt->bind_param("sssi", $name, $date, $desc, $idevent);
        $stmt->execute();

        $affected = $stmt->affected_rows;

        echo "<br><br>";
        echo "Data updated successfully!";
        echo "<br><br>";
        echo "<a href='event.php'>Back to Event</a>";

        $stmt->close();
    }

    $mysqli->close();
?>