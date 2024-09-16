<?php 
// header.php
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$user = "admin"; 
?>

<header class="header">
    <div class="containerLogo">
        <img src="img/logo.png" alt="logo" class="logo">
    </div>
    <div class="nav">
        <div class="nav-kiri">
            <a href="home.php" <?php echo "style = 'display:".(($user=="admin")?"yes":"none")."';"?>><nav class="navbar">HOME</nav></a>
            <a href="event.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">EVENT</nav></a>
            <a href="game.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">DIVISION</nav></a>
            <a href="team.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">TEAM</nav></a>
            <a href="recruitment.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">RECRUITMENT</nav></a>
            <a href="manage.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">MANAGE</nav></a> 
        </div>
        <div class="nav-kanan">
            <button class="btn-login">LOGIN</button>
        </div>
    </div>
</header>