<?php 
    session_start();
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    if (isset($_POST['btnAddEv'])) {
        $idevent = $_POST['idevent'];
        $idteam = $_POST['idteam'];

        if (!empty($idevent) && !empty($idteam)) {
            $stmt = $mysqli->prepare("INSERT INTO event_teams (idevent, idteam) VALUES (?, ?)");
            $stmt->bind_param("ii", $idevent, $idteam);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Tim berhasil ditambahkan ke event!');
                        window.location.href='join_event.php?idevent=$idevent';
                    </script>";
            } 
            else {
                echo "<script>
                        alert('Gagal menambahkan tim.');
                        window.location.href='manage.php?result=failed';
                    </script>";
            }
            $stmt->close();
        } 
        else {
            echo "<script>
                    alert('Event ID atau Team ID kosong!');
                    window.location.href='manage.php';
                </script>";
        }
    }
    $mysqli->close();
?>
