<?php
    session_start();
    require_once('teamClass.php');

    $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
    $idteam = isset($_GET['idteam']) ? $_GET['idteam'] : null;

    $team = new Team();
    $team_name = $team->getTeamById($idteam)['name'];
    
    $profile_picture = "uploads/" . $idteam . ".jpg";
    if (!file_exists($profile_picture)) {
        $profile_picture = "img/default_pp.jpg";
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
                <!-- Profile Picture Display -->
                <div class="profile-container">
                    <div class="profile-picture">
                        <img id="profile-img" src="<?= htmlspecialchars($profile_picture) ?>" alt="Profile Picture">
                    </div>
                    <?php if ($role === 'admin'): ?>
                        <!-- Profile Picture Upload Form -->
                        <form id="upload-form" enctype="multipart/form-data" class="upload-form">
                            <label for="profile_picture">Change</label>
                            <input type="file" name="profile_picture" id="profile_picture" accept=".jpg" required>
                            <button type="button" onclick="uploadImage()">Save</button> 
                            <input type="hidden" name="idteam" value="<?= htmlspecialchars($idteam) ?>"> <!-- Send idteam as hidden input -->
                            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>"> <!-- Send role as hidden input -->
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </article>
    </main>
    <?php include('footer.php'); ?>

    <!-- JavaScript for AJAX upload and preview -->
    <script>
        // Function to preview the selected image by updating the main profile image
        document.getElementById('profile_picture').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type === 'image/jpeg') {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                alert('Please select a valid JPG file.');
            }
        });

        // AJAX function to upload the image
        function uploadImage() {
            const formData = new FormData(document.getElementById('upload-form'));
            formData.append('idteam', '<?= htmlspecialchars($idteam) ?>'); // Explicitly append idteam

            fetch('upload_pp.php', {
                method: 'POST',
                body: formData,
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json(); // Parse response as JSON
            })
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('profile-img').src = data.imagePath + '?' + new Date().getTime(); // Force image refresh
                    alert(data.message);
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("An error occurred. Please check the console for details.");
            });
        }
    </script>
</body>
</html>