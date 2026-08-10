<div style="background: var(--ivory); color: var(--ink);">

    <!-- Search Header -->
    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><span>Search</span></div>
            <div class="eyebrow center reveal">Storefront</div>
            <h1 class="display reveal">Search <span class="gold-text">Results</span></h1>
            <p class="lead reveal">Showing <strong><?= count($products) ?></strong> product<?= count($products) === 1 ? '' : 's' ?> for "<em><?= htmlspecialchars($query) ?></em>"</p>

            <!-- Interactive Search Bar -->
            <div class="search-wrap reveal" x-data="searchModule()">
                <div class="search-box">
                    <svg class="ic" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchResults" @focus="showDropdown = true" @click.away="showDropdown = false" placeholder="Search for products, categories, or articles..." aria-label="Search">
                    <button x-show="searchQuery.length > 0" @click="searchQuery = ''; results = null" class="clear" aria-label="Clear search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>

                <!-- AJAX Dropdown -->
                <div x-show="showDropdown && (results || popular.length > 0)" class="search-dd" style="display: none;">

                    <!-- Loading State -->
                    <div x-show="isLoading" class="sd-loading">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        Searching...
                    </div>

                    <!-- Empty State (No Results) -->
                    <div x-show="!isLoading && results && Object.keys(results).length === 0" class="sd-empty">
                        <p>No exact matches found for "<strong x-text="searchQuery"></strong>"</p>
                        <div x-show="suggestions.length > 0">
                            <div class="sd-label">Suggestions</div>
                            <div class="tag-list">
                                <template x-for="s in suggestions"><button x-text="s"></button></template>
                            </div>
                        </div>
                    </div>

                    <!-- Results State -->
                    <div x-show="!isLoading && results && Object.keys(results).length > 0">
                        <div class="sd-groups">
                            <template x-for="(groupData, groupName) in results">
                                <div class="sd-group">
                                    <h3 class="sd-label" x-text="groupName"></h3>
                                    <template x-for="item in groupData">
                                        <a :href="item.url" class="sd-item">
                                            <template x-if="item.image">
                                                <span class="sd-thumb"><img :src="item.image" :alt="item.title"></span>
                                            </template>
                                            <template x-if="!item.image">
                                                <span class="sd-thumb">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                                </span>
                                            </template>
                                            <span class="sd-title" x-text="item.title"></span>
                                            <span x-if="item.price" class="sd-price" x-text="item.price"></span>
                                        </a>
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="search-foot">
                            <a :href="'/search?q=' + encodeURIComponent(searchQuery)" class="btn btn-gold">View all results
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>

                    <!-- Initial State (Popular Searches) -->
                    <div x-show="!isLoading && !results && popular.length > 0" class="sd-empty" style="text-align: left;">
                        <div class="sd-label">Popular Searches</div>
                        <div class="tag-list" style="justify-content: flex-start;">
                            <template x-for="p in popular">
                                <button @click="searchQuery = p; fetchResults()" x-text="p"></button>
                            </template>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Full Results Body -->
    <section class="section" style="padding-top: 20px;">
        <div class="container">
            <div class="shop-layout">

                <!-- Filters Sidebar -->
                <aside class="shop-side reveal d1">
                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20.59 13.41 11 3.83A2 2 0 0 0 9.59 3.22H4a1 1 0 0 0-1 1v5.59a2 2 0 0 0 .59 1.41l9.59 9.59a2 2 0 0 0 2.82 0l4.59-4.59a2 2 0 0 0 0-2.81Z"/><path d="M7 7h.01"/></svg>
                            Categories
                        </h3>
                        <div class="side-list">
                            <a class="active" href="/search?q=<?= urlencode($query) ?>"><span>Notebooks</span> <span>4</span></a>
                            <a href="/search?q=<?= urlencode($query) ?>"><span>Gift Sets</span> <span>12</span></a>
                            <a href="/search?q=<?= urlencode($query) ?>"><span>Drinkware</span> <span>8</span></a>
                        </div>
                    </div>

                    <div class="side-card">
                        <h3 class="side-h">
                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3h18v18H3z"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                            Price Range
                        </h3>
                        <div class="range-row">
                            <div class="range-input">
                                <span>&#8358;</span>
                                <input type="number" min="0" step="500" placeholder="Min" aria-label="Minimum price">
                            </div>
                            <span class="range-sep">&ndash;</span>
                            <div class="range-input">
                                <span>&#8358;</span>
                                <input type="number" min="0" step="500" placeholder="Max" aria-label="Maximum price">
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Product Grid -->
                <div class="flex-1 min-w-0">
                    <div class="grid-head reveal">
                        <p class="shop-count">Showing <strong><?= count($products) ?></strong> product<?= count($products) === 1 ? '' : 's' ?></p>
                        <div class="sort-box">
                            <select aria-label="Sort products">
                                <option>Sort by: Relevance</option>
                                <option>Price: Low to High</option>
                                <option>Price: High to Low</option>
                                <option>Newest Arrivals</option>
                            </select>
                        </div>
                    </div>

                    <div class="pgrid search-grid">
                        <?php foreach ($products as $p): ?>
                        <article class="pcard reveal">
                            <div class="img-wrap">
                                <a class="img-link" href="/product/<?= $p['id'] ?>" aria-label="View <?= htmlspecialchars($p['name']) ?>">
                                    <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy">
                                </a>
                                <button class="cart-fab" data-act="add" data-id="<?= $p['id'] ?>" aria-label="Add <?= htmlspecialchars($p['name']) ?> to cart">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                </button>
                            </div>
                            <div class="p-body">
                                <span class="p-cat"><?= htmlspecialchars($p['category']) ?></span>
                                <a class="p-name" href="/product/<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></a>
                                <div class="p-foot">
                                    <span class="price">&#8358;<?= number_format($p['price']) ?></span>
                                    <a class="link-more" href="/product/<?= $p['id'] ?>">Details <span class="arr">&rarr;</span></a>
                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('searchModule', () => ({
        searchQuery: '<?= htmlspecialchars($query, ENT_QUOTES) ?>',
        showDropdown: false,
        isLoading: false,
        results: null,
        suggestions: [],
        popular: [],

        init() {
            // Fetch popular on init if query is empty
            if (!this.searchQuery) this.fetchResults();
        },

        async fetchResults() {
            if (this.searchQuery.length > 0 && this.searchQuery.length < 2) return;

            this.isLoading = true;
            this.showDropdown = true;

            try {
                const response = await fetch('/api/search?q=' + encodeURIComponent(this.searchQuery));
                const data = await response.json();

                this.results = data.results || null;
                this.suggestions = data.suggestions || [];
                this.popular = data.popular || [];
            } catch (error) {
                console.error("Search failed:", error);
            } finally {
                this.isLoading = false;
            }
        }
    }));
});
</script>
