<?php 
  require_once("achievementClass.php");
  if (isset($_GET['idachievement']) && !empty($_GET['idachievement'])) {
      $idachievement = $_GET['idachievement'];
      $achievement = new Achievement();

      if ($achievement->deleteAchievement($idachievement) > 0) {
        echo "<script>
                alert('Data deleted successfully!');
                window.location.href='index.php?result=deleted';
              </script>";
      } 
      else {
        echo "<script>
                alert('Error: Achievement could not be deleted.');
                window.location.href='index.php';
              </script>";
      }
  } 
  else {
      echo "<script>
              alert('Invalid Achievement ID.');
              window.location.href='index.php';
            </script>";
  }
?>