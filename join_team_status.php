<?php 
session_start();

$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
$user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$idmember = isset($_SESSION['idmember']) ? $_SESSION['idmember'] : null;

$idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;
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
    <title>Join Proposal Status</title>
</head>
<body>
    <?php 
        include('header.php');
    ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">Join Proposal Status</h1>
            </div>
            <br><br><br>
            <div class="content-page">
                <table class="tableEvent">
                    <thead>
                        <tr>
                            <th scope="col">Team Name</th>
                            <th scope="col">Role</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $result = $mysqli->query("SELECT t.idteam, t.name, jp.description, jp.status FROM join_proposal jp INNER JOIN team t ON jp.idteam = t.idteam WHERE jp.idmember = '$idmember'");
                            while ($row = $result->fetch_assoc()) {
                                $idteam = $row['idteam'];
                                $team = $mysqli->query("SELECT * FROM team WHERE idteam = '$idteam'")->fetch_assoc();
                                echo "<tr>";
                                echo "<td>" . $team['name'] . "</td>";
                                echo "<td>" . $row['description'] . "</td>";
                                if ($row['status'] == "waiting") {
                                    echo "<td style='color: lightblue; font-weight: bold;'>". $row['status'] ."</td>";
                                }

                                else if ($row['status'] == "accepted") {
                                    echo "<td style='color: green; font-weight: bold;'>". $row['status'] ."</td>";
                                }

                                else if ($row['status'] == "rejected") {
                                    echo "<td style='color: red; font-weight: bold;'>". $row['status'] ."</td>";
                                }
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </article>
    </main>
    <?php 
        include('footer.php');
    ?>
</body>
</html>