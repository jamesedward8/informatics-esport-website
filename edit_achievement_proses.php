<?php 
    require_once("achievementClass.php");

    if (isset($_POST['btnEditEv'])) {
        $achievement = new Achievement();

        $data = [
            'idteam' => $_POST['idteam'],
            'name' => $_POST['name'],
            'date' => $_POST['date'],   
            'desc' => $_POST['desc'],
            'idachievement' => $_POST['idachievement']
        ];

        if ($achievement->updateAchievements($data) > 0) {
            echo "<script>
                    alert('Data updated successfully!');
                    window.location.href='index.php?idevent={$data['idachievement']}&result=updated';
                </script>";
        } 
        else {
            echo "<script>
                    alert('Error: Failed to update achievement data.');
                    window.location.href='edit_achievement.php?idachievement={$data['idachievement']}';
                </script>";
        }
    }
?>