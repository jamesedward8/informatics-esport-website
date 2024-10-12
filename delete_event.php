<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    if (isset($_GET['idevent'])) {
        if ($_GET['idevent'] != null) {
            $idevent = $_GET['idevent'];

            $stmt = $mysqli->prepare("DELETE FROM event WHERE idevent = ?");
            $stmt->bind_param("i", $idevent);
            $stmt->execute();

            $affected = $stmt->affected_rows;

            echo "<script>
                    alert('Data deleted successfully!');
                    window.location.href='event.php?idevent=$idevent&result=updated';
                </script>";

            $stmt->close();
        }
    }
    $mysqli->close();
?>