<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    if (isset($_POST['btnEditEv'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("UPDATE game SET name = ?,  description = ? WHERE idgame = ?");
        $stmt->bind_param("ssi", $name, $desc, $idgame);
        $stmt->execute();

        $affected = $stmt->affected_rows;
        $stmt->close();

        echo "<script>
                alert('Data updated successfully!');
                window.location.href='game.php?idgame=$idgame&result=updated';
            </script>";
    }
    $mysqli->close();
?>