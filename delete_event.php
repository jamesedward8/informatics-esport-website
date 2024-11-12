<?php 
    require_once("eventClass.php");

    if (isset($_GET['idevent']) && !empty($_GET['idevent'])) {
      $idevent = $_GET['idevent'];
      $event = new Event();

      if ($event->deleteEvent($idevent) > 0) {
          echo "<script>
                  alert('Data deleted successfully!');
                  window.location.href='event.php?result=deleted';
                </script>";
      } 
      else {
          echo "<script>
                  alert('Error: Event could not be deleted.');
                  window.location.href='event.php';
                </script>";
      }
    } 
    else {
      echo "<script>
              alert('Invalid Event ID.');
              window.location.href='event.php';
            </script>";
    }
?>