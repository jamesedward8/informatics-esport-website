<?php 
    require_once("eventClass.php");

    if (isset($_POST['btnEditEv'])) {
        $event = new Event();

        $data = [
            'name' => $_POST['name'],
            'date' => $_POST['date'],
            'desc' => $_POST['desc'],   
            'idevent' => $_POST['idevent']
        ];

        if ($event->updateEvent($data) > 0) {
            echo "<script>
                    alert('Data updated successfully!');
                    window.location.href='event.php?idevent={$data['idevent']}&result=updated';
                </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to update event data.');
                    window.location.href='edit_event.php?idevent={$data['idevent']}';
                </script>";
        }
    }
?>