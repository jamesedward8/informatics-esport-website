<?php
session_start(); 
$_SESSION = [];
session_destroy();
echo "<script>
    alert('Log Out Successful');
    window.location.href = 'home.php';
</script>";
exit;