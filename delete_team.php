<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
    
    if (isset($_GET['idteam'])) {
        if ($_GET['idteam'] != null) {
            $idteam = $_GET['idteam'];

            $stmt = $mysqli->prepare("DELETE FROM team WHERE idteam = ?");
            $stmt->bind_param("i", $idteam);
            $stmt->execute();

            $affected = $stmt->affected_rows;

            echo "<script>
                    alert('Data deleted successfully!');
                    window.location.href='team.php?idevent=$idteam&result=updated';
                </script>";

            $stmt->close();
        }
    }
    $mysqli->close();
?>