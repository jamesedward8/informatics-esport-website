<?php
    session_start();
    require_once('eventClass.php');
    require_once('pagination.php');
    
    if (isset($_GET['idevent'])) {
        $idevent = $_GET['idevent'];
    } else {
        echo "Event ID is missing!";
        exit();
    }

    $limit = 3;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    $pageEvent = new Event();
    $totalData = $pageEvent->getTotalJoinEvent($idevent);
    $totalPages = ceil($totalData / $limit);
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
        <title>Add Team to Event - Informatics Esports</title>
    </head>

    <body>
        <?php
        include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Add Team to Event</h1>
                </div>
                <div class="content-page">
                    <form action="join_event_proses.php" method="POST">
                        = <input type="hidden" name="idevent" value="<?php echo $idevent; ?>">
                        <div class="form-section">
                            <label for="idteam" class="form-label label-add-event">Pilih Tim:</label>
                            <select name="idteam" class="form-control input-add-event" id="idteam" required>
                                <option value="">-- Pilih Tim --</option>
                                <?php
                                $event = new Event();
                                $resEvent = $event->getResultTeam($idevent);
                                while ($row = $resEvent->fetch_assoc()): ?>
                                    <option value="<?php echo $row['idteam']; ?>">
                                        <?php echo $row['name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <input type="submit" class="btn-add-event" value="ADD" name="btnAddEv">
                        </div>
                        <br><br>

                        <div class="content-submenu-page">
                            <h2 class="h2-sub-content-title">Teams that have joined this event:</h2>
                            <?php
                            $event = new Event();
                            $eventTeams = $event->getEventTeams($idevent, $offset, $limit);

                            if ($eventTeams->num_rows > 0) {
                                echo "<table class='tableEvent'>";
                                echo "<thead>";
                                echo "<tr>
                                            <th>Team Name</th>
                                            <th>Action</th>
                                        </tr>";
                                echo "</thead>";

                                echo "<tbody>";
                                while ($row = $eventTeams->fetch_assoc()) {
                                    echo "<tr><td>" . $row['name'] . "</td>
                                        <td><a class='td-btn-delete' href='delete_event_team.php?idevent=" . $row['idevent'] . "&idteam=" . $row['idteam'] . "' style='display:" . (($role == "admin") ? "block" : "none") . ";'>Delete</a></td></tr>";
                                }
                                echo "</tbody>";
                                echo "</table>";
                            } else {
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                echo "<p class='p-sub-content-title'>No teams have joined this event yet.</p>";
                            }
                            ?>
                        </div>
                        <div class="pagination">
                            <?php
                                echo Pagination::createPaginationLinks($page, $totalPages);
                            ?>
                        </div>
                    </form>
                </div>
            </article>
            <?php
            $mysqli->close();
            ?>
        </main>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                if (this.checkValidity()) {
                    var confirmation = confirm("Are you sure you want to add the team to this event?");
                    if (!confirmation) {
                        e.preventDefault();
                    }
                } else {
                    e.preventDefault();
                    alert('Please fill in all required fields.');
                }
            });

            $('.td-btn-delete').click(function(e) {
                var confirmation = confirm("Are you sure you want to delete the team from this event?");

                if (!confirmation) {
                    e.preventDefault();
                }
            });
        });
    </script>
</html>