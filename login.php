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
    <title>Login - Informatics Esports</title>
</head>
<body>
    <?php  
        include('header.php');
    ?>

<main class="content">
        <article>
            <div class="content-title">
                <h1 class="h1-content-title">LOGIN</h1>
            </div>
            <div class="content-page">     
                <form action="login_proses.php" method="POST">
                    <br><br><br>
                    <div class="mb-3">
                        <label for="username" class="form-label, label-add-event">Username:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="text" name="username" class="form-control, input-add-event" id="username" placeholder="Enter your username..." required>
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <label for="password" class="form-label, label-add-event">Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                        <input type="password" name="password" class="form-control, input-add-event" id="password" placeholder="Enter your password..." required>
                    </div>
                    <br><br>

                    <div class="mb-3">
                        <input type="submit" class="btn-add-team" value="LOGIN" name="btnLogin">
                    </div>      
                </form>
            </div>
            
            <p class="label-add-event">Don't have an account?
                <a href="registration.php" class="label-add-event">Register here</a>
            </p>
        </article>
    </main>

    <?php  
        include('footer.php');
    ?>
</body>
</html>