<?php
$allowedPages = [
    'home' => __DIR__ . '/../app/views/home.php',
    'products' => __DIR__ . '/../app/views/products.php',
    'search' => __DIR__ . '/../app/views/search.php',
    'contact' => __DIR__ . '/../app/views/contact.php',
    'login' => __DIR__ . '/../app/views/login.php',
    'register' => __DIR__ . '/../app/views/register.php',
    'forgot-password' => __DIR__ . '/../app/views/forgot-password.php',
];

$page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : 'home';
$viewFile = $allowedPages[$page] ?? __DIR__ . '/../app/views/404.php';

require_once __DIR__ . '/../app/views/layouts/header.php';
require_once __DIR__ . '/../app/views/layouts/navbar.php';
require_once $viewFile;
require_once __DIR__ . '/../app/views/layouts/footer.php';
?>
