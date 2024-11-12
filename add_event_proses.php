<?php 
    require_once('eventClass.php');

    if (isset($_POST['btnAddEv'])) {
        $event = new Event();

        extract($_POST);
        $data = [
            'name' => $name,
            'date' => $date,
            'desc' => $desc,
        ];

        if ($event->addEvent($data) > 0) {
            echo "<script>
                    alert('Data added successfully!');
                    window.location.href='event.php?result=added';
                  </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to add event data.');
                    window.location.href='add_event.php';
                  </script>";
        }
    }
?>