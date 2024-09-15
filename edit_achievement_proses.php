<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }
    else {
        echo "Database connection succeed!";
        echo "<br>";
    }
    
    $user = "admin";

    if (isset($_POST['btnEditEv'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("UPDATE achievement SET idteam = ?,  name = ?, date = ?, description = ? WHERE idachievement = ?");
        $stmt->bind_param("isssi", $idteam, $name, $date, $desc, $idachievement);
        $stmt->execute();

        $affected = $stmt->affected_rows;
        $stmt->close();

        echo "<script>
                alert('Data updated successfully!');
                window.location.href='home.php?idachievement=$idachievement   &result=updated';
            </script>";
    }

    $mysqli->close();
?>