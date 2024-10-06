<?php 
    $mysqli = new mysqli("localhost", "root", "", "esport");

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: " . $mysqli->connect_error;
        exit();
    }

    $user = "admin";

    if (isset($_POST['btnCreateAccount'])) {
        if ($_POST['password'] == $_POST['repassword']){
            extract($_POST);

            $profile = "member";
            $stmt = $mysqli->prepare("INSERT INTO member (fname, lname, username, password, profile) VALUES (?, ?, ?, ?, ?)");
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            if ($username == "admin") {
                $profile = "admin";
            }
            $stmt->bind_param("sssss", $fname, $lname, $username, $hash_password, $profile);

            if ($stmt->execute()) {
                echo "<script>
                        alert('Account created successfully!');
                        window.location.href='login.php?result=success';
                    </script>";
            } else {
                echo "<script>
                        alert('Error creating account. Please try again.');
                        window.history.back();
                    </script>";
            }
            $stmt->close();
        }
        else{
            echo "<script>
                    alert('Passwords do not match. Please make sure both fields are identical.');
                    window.history.back();
                </script>";
        }
    }
    $mysqli->close();
?>