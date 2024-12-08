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

        <!-- Responsive Combo Box -->
        <select class="nav-select" onchange="location = this.value;">
            <option value="" disabled selected>Menu</option>
            <option value="home.php">HOME</option>
            <option value="event.php">EVENT</option>
            <option value="game.php">DIVISION</option>
            <option value="team.php">TEAM</option>
            <?php if ($role == "admin"): ?>
                <option value="join_team_decide.php">RECRUITMENT</option>
            <?php endif; ?>
            <?php if ($role == "admin"): ?>
                <option value="manage.php">MANAGE</option>
            <?php endif; ?>
            <?php if (isset($_SESSION['username'])): ?>
                <option value="logout.php">LOGOUT</option>
            <?php else: ?>
                <option value="login.php">LOGIN</option>
            <?php endif; ?>
        </select>
    </div>
</header>

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
