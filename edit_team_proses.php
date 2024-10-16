<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    if (isset($_POST['btnEditEv'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("UPDATE team SET idgame = ?,  name = ? WHERE idteam = ?");
        $stmt->bind_param("isi", $idgame, $name, $idteam);
        $stmt->execute();

        $affected = $stmt->affected_rows;
        $stmt->close();

        echo "<script>
                alert('Data updated successfully!');
                window.location.href='team.php?idteam=$idteam   &result=updated';
            </script>";
    }
    $mysqli->close();
?>