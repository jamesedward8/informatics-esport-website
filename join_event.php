<?php 
session_start();
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Periksa apakah 'idevent' ada di URL
if (isset($_GET['idevent'])) {
    $idevent = $_GET['idevent'];
} else {
    echo "Event ID is missing!";
    exit();
}

// Ambil daftar tim dari tabel team
$result_team = $mysqli->query("SELECT * FROM team");
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
    <title>Add Team to Event</title>
</head>
<body>
    <?php include('header.php'); ?>
    
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Adding Team to Event</h1>
            </div>
            <div class="content-page">
                <!-- Form untuk menambahkan tim ke event -->
                <form action="join_event_proses.php" method="POST">
                    <!-- Menyembunyikan idevent di input hidden -->
                    <input type="hidden" name="idevent" value="<?php echo $idevent; ?>">
                    
                    <div class="form-section">
                        <label for="idteam" class="form-label label-add-event">Pilih Tim:</label>
                        <select name="idteam" class="form-control input-add-event" id="idteam" required>
                            <option value="">-- Pilih Tim --</option>
                            <?php 
                            // Menampilkan opsi tim dari tabel team
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
                
                <br><br>
                
                <!-- Tabel menampilkan tim yang sudah bergabung dengan event -->
                <div class="content-submenu-page">
                    <h2>Teams that have joined this event:</h2>
                    <?php
                        // Query untuk mengambil tim yang sudah bergabung dalam event ini
                        $stmt = $mysqli->prepare("SELECT t.name FROM event_teams et JOIN team t ON et.idteam = t.idteam WHERE et.idevent = ?");
                        $stmt->bind_param("i", $idevent);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        if ($result->num_rows > 0) {
                            echo "<table class='tableEvent'>";
                            echo "<thead>";
                            echo "<tr>
                                    <th>Team Name</th>
                                  </tr>";
                            echo "</thead>";

                            echo "<tbody>";
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr><td>" . $row['name'] . "</td></tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "<p>No teams have joined this event yet.</p>";
                        }

                        $stmt->close();
                    ?>
                </div>
            </div>
        </article>
    </main>
</body>
</html>

<?php $mysqli->close(); ?>
