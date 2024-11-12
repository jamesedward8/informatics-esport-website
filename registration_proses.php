<?php 
require_once('memberClass.php');

if (isset($_POST['btnCreateAccount'])) {
    if ($_POST['password'] == $_POST['repassword']) {
        $member = new Member();

        $data = [
            'fname' => $_POST['fname'],
            'lname' => $_POST['lname'],
            'username' => $_POST['username'],
            'password' => $_POST['password'],
        ];

        if ($member->checkAvailability($data['username'])) {
            echo "<script>
                    alert('Username already exists.');
                    window.history.back();
                 </script>";
        } 
        else {
            if ($member->addMember($data)) {
                echo "<script>
                        alert('Account created successfully!');
                        window.location.href='login.php?result=success';
                     </script>";
            } 
            else {
                echo "<script>
                        alert('Error creating account. Please try again.');
                        window.history.back();
                     </script>";
            }
        }
    } 
    else {
        echo "<script>
                alert('Passwords do not match. Please make sure both fields are identical.');
                window.history.back();
             </script>";
    }
}
?>