<header class="site-header">
    <div class="container header-container">
        <a class="site-logo" href="index.php?page=home">Seasonal Wardrobe</a>

        <nav class="site-nav" aria-label="Primary navigation">
            <?php
            $isLoggedIn = isset($_SESSION['user_id'], $_SESSION['user_name']);
            $userName = $isLoggedIn ? htmlspecialchars((string) $_SESSION['user_name'], ENT_QUOTES, 'UTF-8') : '';
            ?>
            <ul class="nav-list">
                <li><a class="nav-link" href="index.php?page=home">Home</a></li>
                <li><a class="nav-link" href="index.php?page=products">Products</a></li>
                <li><a class="nav-link" href="index.php?page=search">Search</a></li>
                <li><a class="nav-link" href="index.php?page=contact">Contact</a></li>
                <?php if ($isLoggedIn) : ?>
                    <li><span class="nav-greeting">Hello, <?php echo $userName; ?></span></li>
                    <li><a class="nav-link" href="index.php?page=logout">Logout</a></li>
                <?php else : ?>
                    <li><a class="nav-link" href="index.php?page=login">Login</a></li>
                    <li><a class="nav-link" href="index.php?page=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>
