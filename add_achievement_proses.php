<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $user = "admin";

    if (isset($_POST['btnAddEv'])) {
        extract($_POST);

        $stmt = $mysqli->prepare("INSERT INTO achievement (idteam, name, date, description) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $idteam, $name, $date, $desc);
        $stmt->execute();

        $affected = $stmt->affected_rows;
        $stmt->close();

        echo "<script>
                alert('Data added successfully!');
                window.location.href='home.php?result=added';
             </script>";
    }

    $mysqli->close();
?>