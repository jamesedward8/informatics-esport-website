<?php 
$mysqli = new mysqli("localhost", "root", "", "esport");

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$role = isset($_SESSION['profile']) ? $_SESSION['profile'] : null;
?>

<header class="header">
    <div class="containerLogo">
        <img src="img/logo.png" alt="logo" class="logo">
    </div>
    <div class="nav">   
        <div class="nav-kiri">
            <a href="home.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">HOME</nav></a>
            <a href="event.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">EVENT</nav></a>
            <a href="game.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">DIVISION</nav></a>
            <a href="team.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">TEAM</nav></a>
            <?php 
                if ($role == "admin") {
                    echo "<a href='join_team_decide.php' 'style = 'display:".(isset($_SESSION['username']) ? 'yes' : 'none')."';'><nav class='navbar'>RECRUITMENT</nav></a>";
                }
            ?>
            <a href="manage.php" <?php echo "style = 'display:".($role == 'admin' ? 'yes' : 'none')."';" ?>><nav class="navbar">MANAGE</nav></a> 
        </div>
        <div class="nav-kanan">
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php" id="logout">LOGOUT</a>
            <?php else: ?>
                <button class="btn-login" onclick="window.location.href='login.php';">LOGIN</button>
            <?php endif; ?>
        </div>
    </div>
</header>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        $('#logout').click(function(e) {
            var confirmation = confirm("Are you sure you want to log out?");
            
            if (!confirmation) {
                e.preventDefault();     
            }
        });
    });
</script>