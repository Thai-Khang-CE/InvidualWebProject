<?php
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/breadcrumb.php';

$productModel = new Product();
$categoryModel = new Category();
$categories = [];
$products = [];
$selectedCategorySlug = isset($_GET['category']) && is_string($_GET['category']) ? trim($_GET['category']) : '';
$allowedSorts = [
    'name_asc' => 'Name: A to Z',
    'price_asc' => 'Price: Low to High',
    'price_desc' => 'Price: High to Low',
    'rating_desc' => 'Rating: High to Low',
];
$selectedSort = isset($_GET['sort']) && is_string($_GET['sort']) ? $_GET['sort'] : 'name_asc';
$selectedSort = array_key_exists($selectedSort, $allowedSorts) ? $selectedSort : 'name_asc';
$productsPerPage = 8;
$currentPage = 1;
$totalProducts = 0;
$totalPages = 0;
$offset = 0;
$selectedCategory = null;
$categoryNotFound = false;
$pageTitle = 'Products';
$description = 'Explore seasonal fashion items from Spring, Summer, Autumn, and Winter collections.';
$breadcrumbItems = [
    ['label' => 'Home', 'url' => 'index.php?page=home'],
    ['label' => 'Products', 'url' => null],
];

if (isset($_GET['p']) && is_scalar($_GET['p'])) {
    $requestedPage = (string) $_GET['p'];

    if (ctype_digit($requestedPage) && (int) $requestedPage > 0) {
        $currentPage = (int) $requestedPage;
    }
}

function buildProductsUrl(?string $categorySlug, string $sort, ?int $pageNumber = null): string
{
    $params = ['page' => 'products'];

    if ($categorySlug !== null && $categorySlug !== '') {
        $params['category'] = $categorySlug;
    }

    if ($sort !== 'name_asc') {
        $params['sort'] = $sort;
    }

    if ($pageNumber !== null && $pageNumber > 1) {
        $params['p'] = $pageNumber;
    }

    return 'index.php?' . htmlspecialchars(http_build_query($params), ENT_QUOTES, 'UTF-8');
}

try {
    $categories = $categoryModel->getAllCategories();

    if ($selectedCategorySlug !== '') {
        $selectedCategory = $categoryModel->getCategoryBySlug($selectedCategorySlug);

        if ($selectedCategory === null) {
            $categoryNotFound = true;
            $breadcrumbItems = [
                ['label' => 'Home', 'url' => 'index.php?page=home'],
                ['label' => 'Products', 'url' => 'index.php?page=products'],
                ['label' => 'Category Not Found', 'url' => null],
            ];
        } else {
            $pageTitle = $selectedCategory['name'];
            $breadcrumbItems = [
                ['label' => 'Home', 'url' => 'index.php?page=home'],
                ['label' => 'Products', 'url' => 'index.php?page=products'],
                ['label' => $selectedCategory['name'], 'url' => null],
            ];
        }
    }

    if (!$categoryNotFound) {
        $totalProducts = $productModel->countProducts($selectedCategorySlug !== '' ? $selectedCategorySlug : null);
        $totalPages = $totalProducts > 0 ? (int) ceil($totalProducts / $productsPerPage) : 0;

        if ($totalPages > 0 && $currentPage > $totalPages) {
            $currentPage = $totalPages;
        }

        $offset = ($currentPage - 1) * $productsPerPage;
        $products = $productModel->getProducts($selectedCategorySlug !== '' ? $selectedCategorySlug : null, $selectedSort, $productsPerPage, $offset);
    }
} catch (Throwable $exception) {
    $categories = [];
    $products = [];
    $totalProducts = 0;
    $totalPages = 0;
}
?>
<main class="page-main">
    <section class="products-page">
        <div class="container">
            <?php renderBreadcrumb($breadcrumbItems); ?>

            <div class="products-header">
                <h1><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
                <p><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>

            <div class="category-filters" aria-label="Product categories">
                <a class="category-filter <?php echo $selectedCategorySlug === '' ? 'category-filter--active' : ''; ?>" href="<?php echo buildProductsUrl(null, $selectedSort); ?>">All</a>

                <?php foreach ($categories as $category) : ?>
                    <?php
                    $categoryName = htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8');
                    $categorySlug = $category['slug'];
                    $isActive = $selectedCategorySlug !== '' && $selectedCategorySlug === $category['slug'];
                    ?>
                    <a
                        class="category-filter <?php echo $isActive ? 'category-filter--active' : ''; ?>"
                        href="<?php echo buildProductsUrl($categorySlug, $selectedSort); ?>"
                    >
                        <?php echo $categoryName; ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="products-toolbar">
                <form class="sort-form" action="index.php" method="get">
                    <input type="hidden" name="page" value="products">
                    <?php if ($selectedCategorySlug !== '') : ?>
                        <input type="hidden" name="category" value="<?php echo htmlspecialchars($selectedCategorySlug, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php endif; ?>

                    <label for="sort">Sort by</label>
                    <select name="sort" id="sort">
                        <?php foreach ($allowedSorts as $sortValue => $sortLabel) : ?>
                            <option value="<?php echo htmlspecialchars($sortValue, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selectedSort === $sortValue ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($sortLabel, ENT_QUOTES, 'UTF-8'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Apply</button>
                </form>
            </div>

            <?php if ($categoryNotFound) : ?>
                <div class="page-card">
                    <p>Category not found.</p>
                </div>
            <?php elseif (!empty($products)) : ?>
                <div class="product-grid">
                    <?php foreach ($products as $product) : ?>
                        <?php
                        $productName = htmlspecialchars($product['product_name'], ENT_QUOTES, 'UTF-8');
                        $categoryName = htmlspecialchars($product['category_name'], ENT_QUOTES, 'UTF-8');
                        $productType = htmlspecialchars($product['product_type'], ENT_QUOTES, 'UTF-8');
                        $productSlug = htmlspecialchars($product['product_slug'], ENT_QUOTES, 'UTF-8');
                        $productImage = htmlspecialchars($product['image'], ENT_QUOTES, 'UTF-8');
                        $imagePath = 'images/products/' . $productImage;
                        ?>
                        <article class="product-card">
                            <img
                                class="product-card__image"
                                src="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>"
                                alt="<?php echo $productName; ?>"
                                onerror="this.onerror=null; this.src='images/products/placeholder.svg';"
                            >

                            <div class="product-card__body">
                                <p class="product-card__category"><?php echo $categoryName; ?></p>
                                <h2 class="product-card__title"><?php echo $productName; ?></h2>
                                <p class="product-card__type"><?php echo $productType; ?></p>

                                <div class="product-card__meta">
                                    <span>$<?php echo htmlspecialchars(number_format((float) $product['price'], 2), ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span>Rating: <?php echo htmlspecialchars(number_format((float) $product['rating'], 1), ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span>Stock: <?php echo htmlspecialchars((string) $product['stock'], ENT_QUOTES, 'UTF-8'); ?></span>
                                </div>

                                <a class="product-card__link" href="index.php?page=product&amp;slug=<?php echo $productSlug; ?>">View Details</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1) : ?>
                    <nav class="pagination" aria-label="Products pagination">
                        <?php if ($currentPage > 1) : ?>
                            <a class="pagination-link pagination-arrow" href="<?php echo buildProductsUrl($selectedCategorySlug !== '' ? $selectedCategorySlug : null, $selectedSort, $currentPage - 1); ?>">Previous</a>
                        <?php else : ?>
                            <span class="pagination-link pagination-arrow is-disabled">Previous</span>
                        <?php endif; ?>

                        <?php for ($pageNumber = 1; $pageNumber <= $totalPages; $pageNumber++) : ?>
                            <?php if ($pageNumber === $currentPage) : ?>
                                <span class="pagination-link is-active"><?php echo htmlspecialchars((string) $pageNumber, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php else : ?>
                                <a class="pagination-link" href="<?php echo buildProductsUrl($selectedCategorySlug !== '' ? $selectedCategorySlug : null, $selectedSort, $pageNumber); ?>">
                                    <?php echo htmlspecialchars((string) $pageNumber, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages) : ?>
                            <a class="pagination-link pagination-arrow" href="<?php echo buildProductsUrl($selectedCategorySlug !== '' ? $selectedCategorySlug : null, $selectedSort, $currentPage + 1); ?>">Next</a>
                        <?php else : ?>
                            <span class="pagination-link pagination-arrow is-disabled">Next</span>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            <?php else : ?>
                <div class="page-card">
                    <p><?php echo htmlspecialchars($selectedCategorySlug !== '' ? 'No products found in this category.' : 'No products found.', ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>
