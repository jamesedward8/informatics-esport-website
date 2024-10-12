<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    if (isset($_GET['idevent']) && $_GET['idteam']) {
        if ($_GET['idevent'] != null && $_GET['idteam'] != null) {
            $idevent = $_GET['idevent'];
            $idteam = $_GET['idteam'];

            $stmt = $mysqli->prepare("DELETE FROM event_teams WHERE idevent = ? AND idteam = ?");
            $stmt->bind_param("ii", $idevent, $idteam);
            $stmt->execute();

            $affected = $stmt->affected_rows;

            echo "<script>
                    alert('Data deleted successfully!');
                    window.location.href='join_event.php?idevent=$idevent';
                 </script>";

            $stmt->close();
        }
    }
    $mysqli->close();
?>