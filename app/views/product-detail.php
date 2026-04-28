<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Store.php';
require_once __DIR__ . '/../helpers/breadcrumb.php';

$productModel = new Product();
$storeModel = new Store();
$product = null;
$stores = [];
$productSlug = isset($_GET['slug']) && is_string($_GET['slug']) ? trim($_GET['slug']) : '';
$fromSearch = isset($_GET['from']) && is_string($_GET['from']) ? trim($_GET['from']) === 'search' : false;
$searchKeyword = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
$productNotFound = false;
$breadcrumbItems = [
    ['label' => 'Home', 'url' => 'index.php?page=home'],
    ['label' => 'Products', 'url' => 'index.php?page=products'],
    ['label' => 'Product Not Found', 'url' => null],
];

if ($productSlug === '') {
    $productNotFound = true;
} else {
    try {
        $product = $productModel->getProductBySlug($productSlug);
    } catch (Throwable $exception) {
        $product = null;
    }

    if ($product === null) {
        $productNotFound = true;
    } else {
        try {
            $stores = $storeModel->getStoresByProductId($product['product_id']);
        } catch (Throwable $exception) {
            $stores = [];
        }

        $breadcrumbItems = [
            ['label' => 'Home', 'url' => 'index.php?page=home'],
            ['label' => 'Products', 'url' => 'index.php?page=products'],
            [
                'label' => $product['category_name'],
                'url' => 'index.php?page=products&category=' . rawurlencode($product['category_slug']),
            ],
            ['label' => $product['product_name'], 'url' => null],
        ];
    }
}
?>
<main class="page-main">
    <section class="products-page">
        <div class="container">
            <?php renderBreadcrumb($breadcrumbItems); ?>

            <?php if ($productNotFound) : ?>
                <div class="page-card">
                    <p>Product not found.</p>
                </div>
            <?php else : ?>
                <?php
                $productName = htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8');
                $categoryName = htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8');
                $productType = htmlspecialchars($product['product_type'], ENT_QUOTES, 'UTF-8');
                $productDescription = htmlspecialchars($product['description'], ENT_QUOTES, 'UTF-8');
                $productImage = htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8');
                $imagePath = 'images/products/' . $productImage;
                $price = htmlspecialchars(number_format((float) $product['price'], 2), ENT_QUOTES, 'UTF-8');
                $rating = htmlspecialchars(number_format((float) $product['rating'], 1), ENT_QUOTES, 'UTF-8');
                $stock = (int) $product['stock'];
                $backLinkLabel = 'Back to Products';
                $backLinkHref = 'index.php?page=products';

                if ($fromSearch && $searchKeyword !== '') {
                    $backLinkLabel = 'Back to Search Results';
                    $backLinkHref = 'index.php?page=search&q=' . rawurlencode($searchKeyword);
                }
                ?>
                <div class="product-detail">
                    <div class="product-detail__layout">
                        <div class="product-detail__image-wrap">
                            <img
                                class="product-detail__image"
                                src="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?php echo $productName; ?>"
                            >
                        </div>

                        <div class="product-detail__content">
                            <p class="product-detail__category"><?php echo $categoryName; ?></p>
                            <h1 class="product-detail__title"><?php echo $productName; ?></h1>

                            <div class="product-detail__meta">
                                <span><?php echo $productType; ?></span>
                                <span>Rating: <?php echo $rating; ?></span>
                                <span>
                                    <?php if ($stock > 0) : ?>
                                        In stock: <?php echo htmlspecialchars((string) $stock, ENT_QUOTES, 'UTF-8'); ?> items
                                    <?php else : ?>
                                        Out of stock
                                    <?php endif; ?>
                                </span>
                            </div>

                            <p class="product-detail__price">$<?php echo $price; ?></p>
                            <p class="product-detail__description"><?php echo $productDescription; ?></p>

                            <a class="product-detail__back" href="<?php echo htmlspecialchars($backLinkHref, ENT_QUOTES, 'UTF-8'); ?>">
                                <?php echo htmlspecialchars($backLinkLabel, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </div>
                    </div>

                    <div class="store-availability">
                        <h2 class="store-availability__title">Available at Stores</h2>

                        <?php if (!empty($stores)) : ?>
                            <div class="store-list">
                                <?php foreach ($stores as $store) : ?>
                                    <article class="store-card">
                                        <h3 class="store-card__name"><?php echo htmlspecialchars($store['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                                        <p class="store-card__address">
                                            <?php echo htmlspecialchars($store['address'], ENT_QUOTES, 'UTF-8'); ?>,
                                            <?php echo htmlspecialchars($store['city'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <p class="store-card__quantity">
                                            Available quantity: <?php echo htmlspecialchars((string) $store['quantity'], ENT_QUOTES, 'UTF-8'); ?>
                                        </p>
                                        <a
                                            class="store-card__map-link"
                                            href="<?php echo htmlspecialchars($store['map_url'], ENT_QUOTES, 'UTF-8'); ?>"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            View on Google Maps
                                        </a>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php else : ?>
                            <div class="page-card">
                                <p>This item is currently unavailable in physical stores.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
