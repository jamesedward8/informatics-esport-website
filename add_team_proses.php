<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }


    $user = "admin";

    if (isset($_POST['btnAddEv'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("INSERT INTO team (idgame, name) VALUES (?, ?)");
        $stmt->bind_param("is", $idgame, $name);
        $stmt->execute();

        $affected = $stmt->affected_rows;
        $stmt->close();

        echo "<script>
                alert('Data added successfully!');
                window.location.href='team.php?result=added';
            </script>";
    }

    $mysqli->close();
?>