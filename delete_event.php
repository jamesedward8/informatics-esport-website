<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }


    $user = "admin";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inria+Sans:ital,wght@0,300;0,400;0,700;1,300;1,400;1,700&display=swap" rel="stylesheet">
    <title>Delete Event</title>
</head>
<body>
    <?php  
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Deleting Event</h1>
            </div>
            <div class="content-event">     
            <?php
                if (isset($_GET['idevent'])) {
                    if ($_GET['idevent'] != null) {
                        $idevent = $_GET['idevent'];

                        $stmt = $mysqli->prepare("DELETE FROM event WHERE idevent = ?");
                        $stmt->bind_param("i", $idevent);
                        $stmt->execute();

                        $affected = $stmt->affected_rows;

                        echo "<script>
                                alert('Data deleted successfully!');
                                alert('".$affected." data updated');
                                window.location.href='event.php?idevent=$idevent&result=updated';
                            </script>";

                        $stmt->close();
                    }
                }

                $mysqli->close();
            ?>
            </div>
        </article>

        <?php
            $mysqli->close();
        ?>
    </main>
</body>
</html>