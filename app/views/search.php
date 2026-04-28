<main class="page-main">
    <section class="search-page">
        <div class="container">
            <div class="search-page__header">
                <h1>Search Products</h1>
                <p>Find seasonal fashion items by product name, collection, or product type.</p>
            </div>

            <div class="search-box">
                <div class="search-box__controls">
                    <label class="search-box__label" for="searchInput">Search catalog</label>
                    <input
                        type="text"
                        id="searchInput"
                        class="search-box__input"
                        placeholder="Search for shirts, dresses, shoes, winter coats..."
                    >
                    <button type="button" id="searchButton" class="search-box__button">Search</button>
                </div>

                <p class="search-box__hint">Start typing to search products dynamically without reloading the page.</p>
            </div>

            <div class="search-hints">
                <p class="search-hints__title">Popular searches:</p>
                <div class="search-hints__tags">
                    <button type="button" class="search-hints__tag">Summer shirt</button>
                    <button type="button" class="search-hints__tag">Winter coat</button>
                    <button type="button" class="search-hints__tag">Sneakers</button>
                    <button type="button" class="search-hints__tag">Dress</button>
                </div>
            </div>

            <div id="searchResults" class="search-results">
                <p class="search-results__placeholder">
                    Search results will appear here.
                </p>
            </div>
        </div>
    </section>
</main>
