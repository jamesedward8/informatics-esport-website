<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
    
    if (isset($_GET['idachievement'])) {
        if ($_GET['idachievement'] != null) {
            $idachievement = $_GET['idachievement'];

            $stmt = $mysqli->prepare("DELETE FROM achievement WHERE idachievement = ?");
            $stmt->bind_param("i", $idachievement);
            $stmt->execute();

            $affected = $stmt->affected_rows;

            echo "<script>
                    alert('Data deleted successfully!');
                    window.location.href='home.php?idachievement=$idachievement&result=updated';
                </script>";

            $stmt->close();
        }
    }
    $mysqli->close();
?>