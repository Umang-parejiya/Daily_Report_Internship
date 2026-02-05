<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?php echo isset($page_title) ? $page_title : 'Easy-Cart'; ?></title>
</head>
<body class="page-wrapper">
    <header>
        <div class="container">
            <div class="header-content">
                <a href="index.php" class="logo">EasyCart</a>
                <nav>
                    <ul class="nav-links">
                        <li><a href="index.php" class="nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="plp.php" class="nav-link <?php echo ($current_page == 'products') ? 'active' : ''; ?>">Products</a></li>
                        <li><a href="cart.php" class="nav-link <?php echo ($current_page == 'cart') ? 'active' : ''; ?>">Cart</a></li>
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <li><a href="dashboard.php" class="nav-link <?php echo ($current_page == 'dashboard') ? 'active' : ''; ?>">Dashboard</a></li>
                            <li><a href="logout.php" class="nav-link">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="nav-link <?php echo ($current_page == 'login') ? 'active' : ''; ?>">Login</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>
    <main>
