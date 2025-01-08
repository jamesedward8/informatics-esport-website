<?php 
    require_once('achievementClass.php');

    if (isset($_POST['btnAddEv'])) {
        $achievement = new Achievement();
        
        extract($_POST);
        $data = [
            'idteam' => $idteam,
            'name' => $name,
            'date' => $date,
            'description' => $description
        ];

        if ($achievement->addAchievement($data) > 0) {
            echo "<script>
                    alert('Data added successfully!');
                    window.location.href='index.php?result=added';
                  </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to add achievement data.');
                    window.location.href='add_achievement.php';
                  </script>";
        }
    }
?>