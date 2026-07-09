<div class="max-w-7xl mx-auto px-4 py-8">
    
    <!-- Search Header -->
    <div class="mb-8 border-b border-[var(--border)] pb-8">
        <h1 class="text-3xl font-bold font-manrope mb-4">Search Results for "<?= htmlspecialchars($query) ?>"</h1>
        
        <!-- Interactive Search Bar -->
        <div class="relative max-w-2xl" x-data="searchModule()">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-[var(--text-muted)]"></i>
                <input type="text" x-model="searchQuery" @input.debounce.300ms="fetchResults" @focus="showDropdown = true" @click.away="showDropdown = false" placeholder="Search for products, categories, or articles..." class="w-full bg-[#111] border border-[var(--border)] rounded-full py-3 pl-12 pr-4 text-white focus:outline-none focus:border-[var(--gold)] transition-colors">
                <button x-show="searchQuery.length > 0" @click="searchQuery = ''; results = null" class="absolute right-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] hover:text-white"><i data-lucide="x" class="w-4 h-4"></i></button>
            </div>

            <!-- AJAX Dropdown -->
            <div x-show="showDropdown && (results || popular.length > 0)" class="absolute top-full mt-2 w-full bg-[#111] border border-[var(--border)] rounded-[16px] shadow-2xl z-50 overflow-hidden" style="display: none;">
                
                <!-- Loading State -->
                <div x-show="isLoading" class="p-6 text-center text-[var(--text-muted)]"><i data-lucide="loader-2" class="w-6 h-6 animate-spin mx-auto mb-2"></i> Searching...</div>

                <!-- Empty State (No Results) -->
                <div x-show="!isLoading && results && Object.keys(results).length === 0" class="p-6 text-center">
                    <p class="text-[var(--text-secondary)] mb-4">No exact matches found for "<span x-text="searchQuery" class="text-white"></span>"</p>
                    <div x-show="suggestions.length > 0">
                        <p class="text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-3">Suggestions</p>
                        <div class="flex flex-wrap justify-center gap-2">
                            <template x-for="s in suggestions"><button class="px-3 py-1.5 bg-[var(--surface)] border border-[var(--border)] rounded-full text-xs hover:border-[var(--gold)] transition-colors" x-text="s"></button></template>
                        </div>
                    </div>
                </div>

                <!-- Results State -->
                <div x-show="!isLoading && results && Object.keys(results).length > 0" class="max-h-[60vh] overflow-y-auto p-2">
                    <template x-for="(groupData, groupName) in results">
                        <div class="mb-4 last:mb-0">
                            <h3 class="px-3 py-2 text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold" x-text="groupName"></h3>
                            <div class="space-y-1">
                                <template x-for="item in groupData">
                                    <a :href="item.url" class="flex items-center gap-3 p-2 hover:bg-[var(--surface)] rounded-[10px] transition-colors group/item">
                                        <!-- Image if available (Products) -->
                                        <template x-if="item.image">
                                            <div class="w-10 h-10 rounded border border-[var(--border)] overflow-hidden shrink-0"><img :src="item.image" class="w-full h-full object-cover"></div>
                                        </template>
                                        <!-- Icon for non-products -->
                                        <template x-if="!item.image">
                                            <div class="w-10 h-10 rounded bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center shrink-0 text-[var(--text-muted)] group-hover/item:text-[var(--gold)] transition-colors"><i data-lucide="corner-down-right" class="w-4 h-4"></i></div>
                                        </template>
                                        
                                        <div class="flex-grow min-w-0">
                                            <p class="text-sm font-medium text-white truncate group-hover/item:text-[var(--gold)] transition-colors" x-text="item.title"></p>
                                        </div>
                                        <template x-if="item.price">
                                            <div class="text-sm font-bold text-[var(--gold)] shrink-0" x-text="item.price"></div>
                                        </template>
                                    </a>
                                </template>
                            </div>
                        </div>
                    </template>
                    <div class="p-2 pt-0 mt-2 border-t border-[var(--border)]">
                        <button @click="window.location='/search?q=' + searchQuery" class="w-full py-2 text-center text-sm text-[var(--gold)] hover:bg-[var(--surface)] rounded-[10px] transition-colors font-medium">View all results <i data-lucide="arrow-right" class="w-3.5 h-3.5 inline ml-1"></i></button>
                    </div>
                </div>

                <!-- Initial State (Popular Searches) -->
                <div x-show="!isLoading && !results && popular.length > 0" class="p-4">
                    <p class="text-xs uppercase tracking-wider text-[var(--text-muted)] font-bold mb-3 px-2">Popular Searches</p>
                    <div class="flex flex-wrap gap-2 px-2">
                        <template x-for="p in popular">
                            <button @click="searchQuery = p; fetchResults()" class="px-3 py-1.5 bg-[var(--surface)] border border-[var(--border)] rounded-full text-xs text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors" x-text="p"></button>
                        </template>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Full Results Body -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        
        <!-- Filters Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <div>
                <h3 class="font-bold font-manrope mb-3 text-lg">Categories</h3>
                <ul class="space-y-2 text-sm text-[var(--text-secondary)]">
                    <li><label class="flex items-center gap-2 cursor-pointer hover:text-white"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]" checked> Notebooks (4)</label></li>
                    <li><label class="flex items-center gap-2 cursor-pointer hover:text-white"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]"> Gift Sets (12)</label></li>
                    <li><label class="flex items-center gap-2 cursor-pointer hover:text-white"><input type="checkbox" class="rounded border-[var(--border)] bg-[var(--surface)] accent-[var(--gold)]"> Drinkware (8)</label></li>
                </ul>
            </div>
            
            <div class="border-t border-[var(--border)] pt-6">
                <h3 class="font-bold font-manrope mb-3 text-lg">Price Range</h3>
                <div class="flex items-center gap-2">
                    <input type="number" placeholder="Min" class="input-field w-full text-sm">
                    <span class="text-[var(--text-muted)]">-</span>
                    <input type="number" placeholder="Max" class="input-field w-full text-sm">
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:col-span-3">
            <div class="flex items-center justify-between mb-6">
                <p class="text-[var(--text-secondary)] text-sm">Showing <strong><?= count($products) ?></strong> products</p>
                <select class="input-field h-9 text-sm bg-transparent border-none py-0 pl-0 pr-6 text-white focus:ring-0 cursor-pointer"><option>Sort by: Relevance</option><option>Price: Low to High</option><option>Price: High to Low</option><option>Newest Arrivals</option></select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($products as $p): ?>
                <div class="group cursor-pointer">
                    <div class="relative aspect-square bg-[var(--surface)] rounded-[12px] border border-[var(--border)] overflow-hidden mb-3">
                        <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute top-2 right-2 w-8 h-8 rounded-full bg-black/50 backdrop-blur-md flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity hover:bg-[var(--gold)]"><i data-lucide="heart" class="w-4 h-4"></i></div>
                    </div>
                    <div>
                        <p class="text-xs text-[var(--text-muted)] mb-1 uppercase tracking-wider font-bold"><?= $p['category'] ?></p>
                        <h3 class="font-bold text-sm text-white group-hover:text-[var(--gold)] transition-colors mb-1 truncate"><?= htmlspecialchars($p['name']) ?></h3>
                        <p class="text-[var(--gold)] font-bold text-sm">₦<?= number_format($p['price']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('searchModule', () => ({
        searchQuery: '<?= htmlspecialchars(addslashes($query)) ?>',
        showDropdown: false,
        isLoading: false,
        results: null,
        suggestions: [],
        popular: [],
        
        init() {
            // Fetch popular on init if query is empty
            if(!this.searchQuery) this.fetchResults();
        },

        async fetchResults() {
            if (this.searchQuery.length > 0 && this.searchQuery.length < 2) return;
            
            this.isLoading = true;
            this.showDropdown = true;
            
            try {
                // Mock AJAX call
                const response = await fetch('/api/search?q=' + encodeURIComponent(this.searchQuery));
                const data = await response.json();
                
                this.results = data.results || null;
                this.suggestions = data.suggestions || [];
                this.popular = data.popular || [];
            } catch (error) {
                console.error("Search failed:", error);
            } finally {
                this.isLoading = false;
                lucide.createIcons(); // re-init icons for new DOM
            }
        }
    }));
});
</script>
