<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ob_start();

require_once __DIR__ . '/../app/models/Product.php';

$allowedPages = [
    'home' => __DIR__ . '/../app/views/home.php',
    'products' => __DIR__ . '/../app/views/products.php',
    'product' => __DIR__ . '/../app/views/product-detail.php',
    'search' => __DIR__ . '/../app/views/search.php',
    'contact' => __DIR__ . '/../app/views/contact.php',
    'login' => __DIR__ . '/../app/views/login.php',
    'register' => __DIR__ . '/../app/views/register.php',
    'forgot-password' => __DIR__ . '/../app/views/forgot-password.php',
];

$page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : 'home';

if ($page === 'logout') {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
    header('Location: index.php?page=home');
    exit;
}

if ($page === 'api_search') {
    header('Content-Type: application/json; charset=utf-8');

    $keyword = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';

    if ($keyword === '') {
        echo json_encode([]);
        exit;
    }

    try {
        $productModel = new Product();
        echo json_encode($productModel->searchProducts($keyword, 10));
    } catch (Throwable $exception) {
        $logDirectory = __DIR__ . '/../storage/logs';
        $logFile = $logDirectory . '/search_errors.log';
        $logMessage = '[' . date('Y-m-d H:i:s') . '] ' . $exception->getMessage() . PHP_EOL;

        if (!is_dir($logDirectory)) {
            @mkdir($logDirectory, 0777, true);
        }

        @file_put_contents($logFile, $logMessage, FILE_APPEND);
        http_response_code(500);
        echo json_encode(['error' => 'Search failed']);
    }

    exit;
}

$viewFile = $allowedPages[$page] ?? __DIR__ . '/../app/views/404.php';

require_once __DIR__ . '/../app/views/layouts/header.php';
require_once __DIR__ . '/../app/views/layouts/navbar.php';
require_once $viewFile;
require_once __DIR__ . '/../app/views/layouts/footer.php';
?>
