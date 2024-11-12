<?php 
    session_start();
    require_once('eventClass.php');

    if (isset($_POST['btnAddEv'])) {
        $eventTeams = new Event();
        
        extract($_POST);

        if (!empty($idevent) && !empty($idteam)) {
            $data = [
                'idevent' => $idevent,
                'idteam' => $idteam
            ];

            if ($eventTeams->addEventTeams($data) > 0) {
                echo "<script>
                        alert('Data added successfully!');
                        window.location.href='join_event.php?idevent={$data['idevent']}&result=added';
                      </script>";
            } 
            else {
                echo "<script>
                        alert('Error: Failed to add event teams data.');
                        window.location.href='manage.php';
                      </script>";
            }
        } 
    }
?>
