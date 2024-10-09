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
    <title>Edit Achievement</title>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        // $(document).ready(function() {
        //     function filterTeams() {
        //         var selectedGame = $('#idgame').val();

        //         $('#idteam option').each(function() {
        //             var teamGame = $(this).data('game'); 
        //             if (teamGame == selectedGame || !selectedGame) {
        //                 $(this).show();
        //             } else {
        //                 $(this).hide();
        //             }
        //         });

        //         if ($('#idteam option:selected').data('game') != selectedGame) {
        //             $('#idteam').val('');
        //         }
        //     }

        //     filterTeams();

        //     $('#idgame').change(function() {
        //         filterTeams();
        //     });
        // });
    </script>
</head>
<body>
    <?php  
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Edit Achievement</h1>
            </div>
            <div class="content-page">     
                <?php
                    if (isset($_GET['idevent']) && $_GET['idteam']) {
                        $idevent = $_GET['idevent'];
                        $idteam = $_GET['idteam'];
                        
                        $stmt = $mysqli->prepare("SELECT t.name, t.idteam FROM event_teams et JOIN team t ON et.idteam = t.idteam WHERE et.idevent = ?");
                        $stmt->bind_param("i", $idevent);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $achievement = $res->fetch_assoc();
                    }
                ?>

                    <form action="join_event_proses.php" method="POST">
                   <input type="hidden" name="idevent" value="<?php echo $idevent; ?>">
                    
                    <div class="form-section">
                        <label for="idteam" class="form-label label-add-event">Pilih Tim:</label>
                        <select name="idteam" class="form-control input-add-event" id="idteam" required>
                            <option value="">-- Pilih Tim --</option>
                            <?php 
                            while ($row = $result_team->fetch_assoc()): ?>
                                <option value="<?php echo $row['idteam']; ?>">
                                    <?php echo $row['name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-section">
                        <input type="submit" class="btn-add-event" value="Tambahkan Tim" name="btnAddEv">
                    </div>
                </form>
            </div>
        </article>

        <?php
            $mysqli->close();
        ?>
    </main>
</body>
</html>
