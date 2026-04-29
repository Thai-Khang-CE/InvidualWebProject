document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchInput');
    var searchButton = document.getElementById('searchButton');
    var searchResults = document.getElementById('searchResults');
    var popularTags = document.querySelectorAll('.search-hints__tag');

    if (!searchInput || !searchButton || !searchResults) {
        return;
    }

    var debounceTimer = null;
    var activeController = null;
    var initialParams = new URLSearchParams(window.location.search);

    function clearResults() {
        while (searchResults.firstChild) {
            searchResults.removeChild(searchResults.firstChild);
        }
    }

    function renderMessage(message, extraClassName) {
        clearResults();

        var paragraph = document.createElement('p');
        paragraph.className = extraClassName ? 'search-message ' + extraClassName : 'search-message';
        paragraph.textContent = message;
        searchResults.appendChild(paragraph);
    }

    function createMetaItem(text) {
        var item = document.createElement('span');
        item.textContent = text;
        return item;
    }

    function attachImageFallback(image) {
        image.addEventListener('error', function () {
            if (image.dataset.fallbackApplied === 'true') {
                return;
            }

            image.dataset.fallbackApplied = 'true';
            image.src = 'images/products/placeholder.svg';
        });
    }

    function updateSearchUrl(keyword) {
        var nextUrl = 'index.php?page=search';

        if (keyword !== '') {
            nextUrl += '&q=' + encodeURIComponent(keyword);
        }

        window.history.replaceState({}, '', nextUrl);
    }

    function renderResults(products, keyword) {
        clearResults();

        if (!Array.isArray(products) || products.length === 0) {
            renderMessage('No products found.');
            return;
        }

        var grid = document.createElement('div');
        grid.className = 'search-results__grid';

        products.forEach(function (product) {
            var article = document.createElement('article');
            article.className = 'search-result-card';

            var image = document.createElement('img');
            image.className = 'search-result-card__image';
            image.src = 'images/products/' + String(product.image || '');
            image.alt = String(product.name || 'Product image');
            attachImageFallback(image);
            article.appendChild(image);

            var body = document.createElement('div');
            body.className = 'search-result-card__body';

            var category = document.createElement('p');
            category.className = 'search-result-card__category';
            category.textContent = String(product.category_name || '');
            body.appendChild(category);

            var title = document.createElement('h2');
            title.className = 'search-result-card__title';
            title.textContent = String(product.name || '');
            body.appendChild(title);

            var meta = document.createElement('div');
            meta.className = 'search-result-card__meta';
            meta.appendChild(createMetaItem(String(product.product_type || '')));
            meta.appendChild(createMetaItem('$' + Number(product.price || 0).toFixed(2)));
            meta.appendChild(createMetaItem('Rating: ' + Number(product.rating || 0).toFixed(1)));
            meta.appendChild(createMetaItem('Stock: ' + String(product.stock || 0)));
            body.appendChild(meta);

            var link = document.createElement('a');
            link.className = 'search-result-card__link';
            link.textContent = 'View Details';
            link.setAttribute(
                'href',
                'index.php?page=product&slug=' + encodeURIComponent(String(product.slug || '')) + '&from=search&q=' + encodeURIComponent(keyword)
            );
            body.appendChild(link);

            article.appendChild(body);
            grid.appendChild(article);
        });

        searchResults.appendChild(grid);
    }

    function performSearch() {
        var keyword = searchInput.value.trim();

        if (activeController) {
            activeController.abort();
            activeController = null;
        }

        if (keyword === '') {
            updateSearchUrl('');
            renderMessage('Search results will appear here.', 'search-results__placeholder');
            return;
        }

        updateSearchUrl(keyword);
        renderMessage('Searching...');
        activeController = new AbortController();

        fetch('index.php?page=api_search&q=' + encodeURIComponent(keyword), {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            },
            signal: activeController.signal
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Request failed');
                }

                return response.json();
            })
            .then(function (data) {
                activeController = null;
                renderResults(data, keyword);
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                activeController = null;
                renderMessage('Search failed. Please try again.');
            });
    }

    function queueSearch() {
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(function () {
            performSearch();
        }, 300);
    }

    searchInput.addEventListener('input', queueSearch);
    searchButton.addEventListener('click', function () {
        window.clearTimeout(debounceTimer);
        performSearch();
    });

    popularTags.forEach(function (tag) {
        tag.addEventListener('click', function () {
            searchInput.value = tag.textContent.trim();
            window.clearTimeout(debounceTimer);
            performSearch();
        });
    });

    if (initialParams.has('q')) {
        searchInput.value = initialParams.get('q').trim();
        performSearch();
    }
});
