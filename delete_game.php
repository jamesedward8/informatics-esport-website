<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    if (isset($_GET['idgame'])) {
        if ($_GET['idgame'] != null) {
            $idgame = $_GET['idgame'];

            $stmt = $mysqli->prepare("DELETE FROM game WHERE idgame = ?");
            $stmt->bind_param("i", $idgame);
            $stmt->execute();

            $affected = $stmt->affected_rows;

            echo "<script>
                    alert('Data deleted successfully!');
                    window.location.href='game.php?idevent=$idgame&result=updated';
                </script>";

            $stmt->close();
        }
    }
    $mysqli->close();
?>