<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold mb-2">My Quotes</h1>
        <p class="text-[var(--text-secondary)]">Review bespoke pricing requests and approvals.</p>
    </div>
    
    <div class="flex items-center gap-4">
        <a href="<?= app_url('/quotes/new') ?>" class="btn btn-primary h-[44px] px-6">Request New Quote</a>
    </div>
</div>

<div x-data="quotesTabs()">
    <!-- Tabs -->
    <div class="flex items-center gap-6 border-b border-[var(--border)] mb-8 overflow-x-auto hide-scrollbar">
        <button @click="filter = 'all'" :class="filter === 'all' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">All Quotes</button>
        <button @click="filter = 'pending'" :class="filter === 'pending' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">Pending</button>
        <button @click="filter = 'approved'" :class="filter === 'approved' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">Approved</button>
        <button @click="filter = 'expired'" :class="filter === 'expired' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">Expired</button>
    </div>

    <!-- Quotes List -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <template x-for="quote in visibleQuotes" :key="quote.id">
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors group flex flex-col">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold font-manrope group-hover:text-[var(--gold)] transition-colors mb-1" x-text="quote.id"></h3>
                    <p class="text-sm text-[var(--text-secondary)]" x-text="quote.date"></p>
                </div>
                <span x-show="quote.status === 'Approved'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Approved</span>
                <span x-show="quote.status === 'Expired'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">Expired</span>
                <span x-show="quote.status !== 'Approved' && quote.status !== 'Expired'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20" x-text="quote.status"></span>
            </div>
            
            <div class="flex-grow">
                <p class="text-[var(--text-secondary)] text-sm mb-6"><span class="text-white font-medium" x-text="quote.items"></span> custom items requested.</p>
            </div>
            
            <div class="border-t border-[var(--border)] pt-4 flex items-center justify-between mt-auto">
                <a :href="'/account/quotes/' + quote.id" class="text-[var(--gold)] text-sm font-medium hover:text-white transition-colors flex items-center">
                    Review Details <i data-lucide="arrow-right" class="w-4 h-4 ml-1"></i>
                </a>
            </div>
        </div>
        </template>
    </div>

    <div x-show="visibleQuotes.length === 0" class="p-10 text-center text-[var(--text-muted)]">
        No quotes match this filter.
    </div>
</div>

<script>
    function quotesTabs() {
        const quotes = <?= json_encode(array_map(fn($q) => [
            'id' => $q['id'],
            'date' => date('M j, Y', strtotime($q['date'])),
            'status' => $q['status'],
            'status_key' => $q['status_key'],
            'items' => $q['items'],
        ], $quotes), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?>;
        return {
            filter: 'all',
            quotes: quotes,
            get visibleQuotes() {
                if (this.filter === 'all') return this.quotes;
                return this.quotes.filter(q => q.status_key === this.filter
                    || (this.filter === 'pending' && (q.status_key === 'pending' || q.status_key === 'reviewed')));
            }
        };
    }
</script>
