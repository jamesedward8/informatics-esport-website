<?php
    session_start();
    require_once("teamClass.php");
    require_once('pagination.php');
    require_once('proposalClass.php');

    $mysqli = new mysqli("localhost", "root", "", "esport");


    if ($mysqli->connect_errno) {
        echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
        exit();
    }

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $iduser = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

    $limit = 3;
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $pageAchieve = new Team();
    $totalData = $pageAchieve->getTotalTeam();
    $totalPages = ceil($totalData / $limit);

    $prop = new Proposal();
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
        <title>Team - Informatics Esports</title>
    </head>

    <body>
        <?php  
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Team Dashboard</h1>
                </div>
                <div class="side-content-event">
                    <form action="add_team.php" method="POST">
                        <?php
                            if ($role == "admin") {
                                echo "<input type='submit' class='btn-add-ev' value='ADD' name='btnAdd'>";
                            } else {
                                $resTeam = $prop->viewTeambyId($iduser);

                                if ($row_view_team = $resTeam->fetch_assoc()) {
                                    echo "<a class='btn-view-my-team' href='view_my_team.php?idteam=" . $row_view_team['idteam'] . "'>View My Team</a>";
                                }
                            }
                        ?>
                    </form>
                </div>
                <div class="content-page">
                    <?php
                        $team = new Team();
                        $resTeam = $team->getTeam($offset, $limit);  

                        echo "<br><br>";
                        echo "<table class='tableEvent'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Logo</th>";
                        echo "<th>Team Name</th>";
                        echo "<th>Game Name</th>";
                        
                        if ($role == "admin") {
                            echo "<th colspan=3>Action</th>";
                        } 
                        else if ($role == "member") {
                            echo "<th>Action</th>";
                        }
                        
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        if ($resTeam->num_rows == 0) {
                            echo "<tr><td colspan='5'>No Team Available, Stay Tuned!</td></tr>";
                        } 
                        else {
                            $resApprovedTeam = $prop->getApprovedTeam($iduser);

                            $accepted_games = [];
                            while ($game_row = $resApprovedTeam->fetch_assoc()) {
                                $accepted_games[$game_row['idgame']] = $game_row['idteam'];
                            }

                            while ($row = $resTeam->fetch_assoc()) {
                                $profile_picture = "uploads/" . $row['idteam'] . ".jpg";
                                if (!file_exists($profile_picture)) {
                                    $profile_picture = "img/default_pp.jpg";
                                }

                                echo "<tr>";
                                echo "<td><img src='" . htmlspecialchars($profile_picture) . "' id='profile_pic_" . $row['idteam'] . "' alt='Profile Picture' style='width: 80px;height: 80px; object-fit: cover; border-radius: 50%;'>";
                                if ($role === 'admin'){
                                    echo "<form id='upload-form-{$row['idteam']}' enctype='multipart/form-data' method='POST' action='upload_pp.php' class='upload-form'>";
                                    echo "<label for='profile_picture_{$row['idteam']}'>Change</label>";
                                    echo "<input type='file' name='profile_picture' id='profile_picture_{$row['idteam']}' accept='.jpg' required>";
                                    echo "<button type='button' class='save-btn' data-id='{$row['idteam']}'>Save</button>";
                                    echo "<input type='hidden' name='idteam' value='" . htmlspecialchars($row['idteam']) . "'>";
                                    echo "<input type='hidden' name='role' value='" . htmlspecialchars($role) . "'>";
                                    echo "</form>";
                                }
                                echo "</td>";
                                echo "<td>" . htmlspecialchars($row['namateam']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['namagame']) . "</td>";

                                if ($role == "admin") {
                                    echo "<td><a class='td-btn-edit' href='edit_team.php?idteam=" . $row['idteam'] . "'>Edit</a></td>";
                                    echo "<td><a class='td-btn-delete' href='delete_team.php?idteam=" . $row['idteam'] . "'>Delete</a></td>";
                                } 
                                else if ($role == "member") {
                                    $resWaitingTeam = $prop->getWaitedTeam($row['idteam'], $iduser);

                                    if ($resWaitingTeam->num_rows > 0) {
                                        echo "<td><a class='td-btn-edit' href='join_team_status.php?idteam=" . $row['idteam'] . "&idmember=" . $iduser . "'>See Status</a></td>";
                                    } else {
                                        $resStatus = $prop->getStatusTeam($row['idteam'], $iduser);

                                        if ($resStatus->num_rows > 0) {
                                            $proposal = $resStatus->fetch_assoc();
                                            if ($proposal['status'] == 'approved') {
                                                echo "<td><span style='color: green; font-weight: bold;'>Approved</span></td>";
                                            } 
                                            else if ($proposal['status'] == 'rejected') {
                                                echo "<td><span style='color: darkred; font-weight: bold;'>Rejected</span></td>";
                                            } 
                                            else {
                                                if (isset($accepted_games[$row['idgame']])) {
                                                    echo "<td><span style='color: red; font-weight: bold;'>Not Eligible to Join</span></td>";
                                                } 
                                                else {
                                                    echo "<td><a class='td-btn-edit' href='join_team.php?idteam=" . $row['idteam'] . "&idmember=" . $iduser . "'>Join</a></td>";
                                                }
                                            }
                                        } else {
                                            if (isset($accepted_games[$row['idgame']])) {
                                                echo "<td><span style='color: red; font-weight: bold;'>Not Eligible to Join</span></td>";
                                            } 
                                            else {
                                                echo "<td><a class='td-btn-edit' href='join_team.php?idteam=" . $row['idteam'] . "&idmember=" . $iduser . "'>Join</a></td>";
                                            }
                                        }
                                    }
                                }
                                echo "</tr>";
                            }
                        }
                        echo "</tbody>";
                        echo "</table>";
                    ?>
                </div>
                <div class="pagination">
                    <?php echo Pagination::createPaginationLinks($page, $totalPages); ?>
                </div>
            </article>
        </main>
        <?php include('footer.php'); ?>
    </body>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.td-btn-delete').click(function(e) {
                var confirmation = confirm("Are you sure you want to delete this team?");
                
                if (!confirmation) {
                    e.preventDefault();     
                }
            });

            var originalSrc = {};

            $('img[id^="profile_pic_"]').each(function() {
                var idteam = $(this).attr('id').split('_')[2];
                originalSrc[idteam] = $(this).attr('src');
            });

            $('input[type="file"]').change(function(e) {
                var idteam = $(this).closest('form').find('input[name="idteam"]').val();
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#profile_pic_' + idteam).attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
            });

            $('.save-btn').click(function(e) {
                var idteam = $(this).data('id');
                var confirmation = confirm("Are you sure you want to change the profile picture?");
                
                if (confirmation) {
                    var form = $('#upload-form-' + idteam);
                    var formData = new FormData(form[0]);

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status === 'success') {
                                alert(response.message);
                            } else {
                                alert(response.message);
                                $('#profile_pic_' + idteam).attr('src', originalSrc[idteam]);
                            }
                        },
                        error: function() {
                            alert('There was an error uploading the profile picture.');
                            $('#profile_pic_' + idteam).attr('src', originalSrc[idteam]);
                        }
                    });
                } else {
                    $('#profile_pic_' + idteam).attr('src', originalSrc[idteam]);
                }
            });
        });
    </script>
</html>