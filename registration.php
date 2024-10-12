<?php
    session_start();
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
    $user = isset($_SESSION['username']) ? $_SESSION['username'] : null;
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
        <title>Registration - Informatics Esports</title>
    </head>
    <body>
        <?php  
            include('header.php');
        ?>
        <main class="content">
            <article>
                <div class="content-title">
                    <h1 class="h1-content-title">Register for Your Esports Journey</h1>
                </div>
                <div class="content-page">     
                    <form action="registration_proses.php" method="POST">
                        <br><br>
                        <div class="mb-3">
                            <label for="fname" class="form-label, label-add-event">First Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" name="fname" class="form-control, input-add-event" id="fname" placeholder="Enter your first name..." required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <label for="lname" class="form-label, label-add-event">Last Name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="text" name="lname" class="form-control, input-add-event" id="lname" placeholder="Enter your last name..." required>
                        </div>
                        <br><br>

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
                            <label for="repassword" class="form-label, label-add-event">Re-Password:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                            <input type="password" name="repassword" class="form-control, input-add-event" id="repassword" placeholder="Confrim your password..." required>
                        </div>
                        <br><br>

                        <div class="mb-3">
                            <input type="submit" class="btn-add-team" value="Create Account" name="btnCreateAccount">
                        </div>      
                    </form>
                </div>

                <p class="label-add-event">I already have an account
                    <a href="login.php" class="label-add-event">Login Here</a>
                </p>
            </article>
        </main>
    <?php  
        include('footer.php');
    ?>
    </body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('form').submit(function(e) {
                if (this.checkValidity()) {
                    var confirmation = confirm("Are you sure all data are correct and proceed to register?");
                    if (!confirmation) {
                        e.preventDefault(); 
                    }
                } 
                else {
                    e.preventDefault(); 
                    alert('Please fill in all required fields.'); 
                }
            });
        });
    </script>
</html>