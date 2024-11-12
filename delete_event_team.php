<?php 
    require_once("eventClass.php");

    if (isset($_GET['idevent']) && $_GET['idteam']) {
        if ($_GET['idevent'] != null && $_GET['idteam'] != null) {
            $idevent = $_GET['idevent'];
            $idteam = $_GET['idteam'];
            $eventTeams = new Event();

            if ($eventTeams->deleteEventTeams($idevent, $idteam) > 0) {
                echo "<script>
                        alert('Data deleted successfully!');
                        window.location.href='join_event.php?idevent=$idevent&result=deleted';
                      </script>";
            } 
            else {
                echo "<script>
                        alert('Error: Event teams could not be deleted.');
                        window.location.href='join_event.php';
                      </script>";
            }
        } 
    }
    else {
        echo "<script>
                alert('Invalid Event Teams ID.');
                window.location.href='join_event.php';
                </script>";
    }
?>