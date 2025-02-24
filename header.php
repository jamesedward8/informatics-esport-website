<?php
$mysqli = new mysqli("localhost", "root", "", "fullstack");

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
            <a href="index.php" <?php echo "style = 'display:yes';" ?>>
                <nav class="navbar">HOME</nav>
            </a>
            <a href="event.php" <?php echo "style = 'display:yes';" ?>>
                <nav class="navbar">EVENT</nav>
            </a>
            <a href="game.php" <?php echo "style = 'display:yes';" ?>>
                <nav class="navbar">DIVISION</nav>
            </a>
            <a href="team.php" <?php echo "style = 'display:yes';" ?>>
                <nav class="navbar">TEAM</nav>
            </a>
            <?php
            if ($role == "admin") {
                echo "<a href='join_team_decide.php' 'style = 'display:" . (isset($_SESSION['username']) ? 'yes' : 'none') . "';'><nav class='navbar'>RECRUITMENT</nav></a>";
            }
            ?>
            <a href="manage.php" <?php echo "style = 'display:" . ($role == 'admin' ? 'yes' : 'none') . "';" ?>>
                <nav class="navbar">MANAGE</nav>
            </a>
        </div>
        <div class="nav-kanan">
            <?php if (isset($_SESSION['username'])): ?>
                <p>Welcome, <?= $_SESSION['username'] ?></p>
                <a href="logout.php" id="logout">LOGOUT</a>
            <?php else: ?>
                <button class="btn-login" onclick="window.location.href='login.php';">LOGIN</button>
            <?php endif; ?>
        </div>

        <!-- Responsive Combo Box -->
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        ?>
        <select class="nav-select" onchange="location = this.value;">
            <option value="" disabled <?php echo empty($current_page) ? 'selected' : ''; ?>>Menu</option>
            <option value="index.php" <?php echo $current_page == 'index.php' ? 'selected' : ''; ?>>HOME</option>
            <option value="event.php" <?php echo $current_page == 'event.php' ? 'selected' : ''; ?>>EVENT</option>
            <option value="game.php" <?php echo $current_page == 'game.php' ? 'selected' : ''; ?>>DIVISION</option>
            <option value="team.php" <?php echo $current_page == 'team.php' ? 'selected' : ''; ?>>TEAM</option>
            <?php if ($role == "admin"): ?>
                <option value="join_team_decide.php" <?php echo $current_page == 'join_team_decide.php' ? 'selected' : ''; ?>>RECRUITMENT</option>
                <option value="manage.php" <?php echo $current_page == 'manage.php' ? 'selected' : ''; ?>>MANAGE</option>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])): ?>
                <option value="logout.php" <?php echo $current_page == 'logout.php' ? 'selected' : ''; ?>>LOGOUT</option>
            <?php else: ?>
                <option value="login.php" <?php echo $current_page == 'login.php' ? 'selected' : ''; ?>>LOGIN</option>
            <?php endif; ?>
        </select>
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