<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }


    $user = "admin";

    if (isset($_POST['btnEditEv'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("UPDATE event SET name = ?, date = ?, description = ? WHERE idevent = ?");
        $stmt->bind_param("sssi", $name, $date, $desc, $idevent);
        $stmt->execute();

        $affected = $stmt->affected_rows;
        $stmt->close();

        echo "<script>
                alert('Data updated successfully!');
                window.location.href='event.php?idevent=$idevent&result=updated';
            </script>";
    }

    $mysqli->close();
?>