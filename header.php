<header class="header">
        <div class="overlay" data-overlay></div>

        <div class="containerLogo">
            <img src="img/logo.png" alt="logo" class="logo">
        </div>
        <div class="nav">
            <div class="nav-kiri">
                <a href="home.php" <?php echo "style = 'display:".(($user=="admin")?"yes":"none")."';"?>><nav class="navbar">Home</nav></a>
                <a href="event.php" <?php echo "style = 'display:yes';" ?>><nav class="navbar">Event</nav></a>
                <nav class="navbar">Division</nav>  
                <nav class="navbar">Our Team</nav>
                <nav class="navbar">Recruitment</nav>
                <nav class="navbar">Manage </nav>  
            </div>
            <div class="nav-kanan">
                <button>Login</button>
            </div>
        </div>
    </header>