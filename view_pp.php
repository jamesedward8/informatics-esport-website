<?php 
    session_start();
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
    $iduser = isset($_SESSION['iduser']) ? $_SESSION['iduser'] : null;

    $idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;
    
    // Handle profile picture upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture']) && $role == 'admin') {
        $target_dir = "uploads/teams/";
        $target_file = $target_dir . "team_" . $idteam . ".jpg"; // Rename to team ID for uniqueness
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a real image
        $check = getimagesize($_FILES['profile_picture']['tmp_name']);
        if ($check === false) {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Allow only certain formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            echo "Only JPG, JPEG, and PNG files are allowed.";
            $uploadOk = 0;
        }

        // Check file size (limit to 2MB)
        if ($_FILES['profile_picture']['size'] > 2000000) {
            echo "Your file is too large. Max size is 2MB.";
            $uploadOk = 0;
        }

        // Attempt to upload if no errors
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                echo "The profile picture has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    // Get team name and check if profile picture exists
    $stmt_team_name = $mysqli->prepare("SELECT name FROM team WHERE idteam = ?");
    $stmt_team_name->bind_param('i', $idteam);
    $stmt_team_name->execute();
    $res_team_name = $stmt_team_name->get_result();
    $team = $res_team_name->fetch_assoc();
    $team_name = $team['name'] ?? 'Unknown Team';

    $profile_picture = "uploads/teams/team_" . $idteam . ".jpg";
    if (!file_exists($profile_picture)) {
        $profile_picture = "images/default_profile.png"; // Default image if no upload
    }
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
    <title>View Team Profile</title>
</head>
<body>
    <?php include('header.php'); ?>
    <main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">View Team <?= htmlspecialchars($team_name) ?>'s Profile</h1>
            </div>
            <div class="content-page">
                <!-- Display Profile Picture -->
                <div class="profile-picture">
                    <img src="<?= htmlspecialchars($profile_picture) ?>" alt="" style="width: 200px; height: 200px; border-radius: 50%;">
                </div>

                <?php if ($role == 'admin'): ?>
                    <!-- Profile Picture Upload Form (only visible to admins) -->
                    <form action="view_pp.php?idteam=<?= $idteam ?>" method="POST" enctype="multipart/form-data">
                        <label for="profile_picture" style="color:burlywood;">Change Profile Picture:</label>
                        <input type="file" name="profile_picture" id="profile_picture" accept="uploads/*" required>
                        <button type="submit">Upload</button>
                    </form>
                <?php endif; ?>
            </div>
        </article>
    </main>
    <?php include('footer.php'); ?>
</body>
</html>