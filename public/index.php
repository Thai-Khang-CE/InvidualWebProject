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
$pageTitle = 'Seasonal Wardrobe';
$pageDescription = 'Seasonal Wardrobe is a seasonal fashion store for Spring, Summer, Autumn, and Winter collections.';
$preloadedProduct = null;

$truncateText = static function (string $text, int $length = 150): string {
    $text = trim(preg_replace('/\s+/', ' ', $text) ?? '');

    if ($text === '') {
        return '';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) <= $length) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $length - 3)) . '...';
    }

    if (strlen($text) <= $length) {
        return $text;
    }

    return rtrim(substr($text, 0, $length - 3)) . '...';
};

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

switch ($page) {
    case 'home':
        $pageTitle = 'Home';
        $pageDescription = 'Discover Seasonal Wardrobe, a fashion store organized by Spring, Summer, Autumn, and Winter collections.';
        break;
    case 'products':
        $pageTitle = 'Products';
        $pageDescription = 'Browse seasonal fashion products including shirts, dresses, shoes, jackets, and accessories.';
        break;
    case 'search':
        $pageTitle = 'Search Products';
        $pageDescription = 'Search seasonal fashion products dynamically by name, collection, or product type.';
        break;
    case 'contact':
        $pageTitle = 'Contact';
        $pageDescription = 'Contact Seasonal Wardrobe and find our store locations in Ho Chi Minh City.';
        break;
    case 'login':
        $pageTitle = 'Login';
        $pageDescription = 'Log in to your Seasonal Wardrobe account.';
        break;
    case 'register':
        $pageTitle = 'Register';
        $pageDescription = 'Create a Seasonal Wardrobe account to access member features.';
        break;
    case 'forgot-password':
        $pageTitle = 'Forgot Password';
        $pageDescription = 'Reset your Seasonal Wardrobe account password.';
        break;
    case 'product':
        $productSlug = isset($_GET['slug']) && is_string($_GET['slug']) ? trim($_GET['slug']) : '';

        if ($productSlug === '') {
            $pageTitle = 'Product Not Found';
            $pageDescription = 'The requested product could not be found at Seasonal Wardrobe.';
            break;
        }

        try {
            $productModel = new Product();
            $preloadedProduct = $productModel->getProductBySlug($productSlug);
        } catch (Throwable $exception) {
            $preloadedProduct = null;
        }

        if ($preloadedProduct === null) {
            $pageTitle = 'Product Not Found';
            $pageDescription = 'The requested product could not be found at Seasonal Wardrobe.';
        } else {
            $pageTitle = $preloadedProduct['product_name'];
            $pageDescription = $truncateText((string) $preloadedProduct['description']);

            if ($pageDescription === '') {
                $pageDescription = 'Explore this seasonal fashion item at Seasonal Wardrobe.';
            }
        }
        break;
    default:
        $pageTitle = 'Page Not Found';
        $pageDescription = 'The page you are looking for could not be found.';
        break;
}

require_once __DIR__ . '/../app/views/layouts/header.php';
require_once __DIR__ . '/../app/views/layouts/navbar.php';
require_once $viewFile;
require_once __DIR__ . '/../app/views/layouts/footer.php';
?>
