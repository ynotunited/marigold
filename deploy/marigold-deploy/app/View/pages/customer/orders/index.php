<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h1 class="text-3xl font-bold mb-2">My Orders</h1>
        <p class="text-[var(--text-secondary)]">Track, manage, and view history of your corporate orders.</p>
    </div>
    
    <!-- Filters / Search -->
    <div class="flex items-center gap-4 w-full md:w-auto">
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Search orders..." x-model="search" class="input-field w-full pl-10 h-11 bg-[var(--surface)] border-[var(--border)]">
            <i data-lucide="search" class="w-4 h-4 text-[var(--text-muted)] absolute left-4 top-1/2 -translate-y-1/2"></i>
        </div>
    </div>
</div>

<div class="mb-8" x-data="ordersTabs()">
    <!-- Tabs -->
    <div class="flex items-center gap-6 border-b border-[var(--border)] mb-8 overflow-x-auto hide-scrollbar">
        <button @click="filter = 'all'" :class="filter === 'all' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">All Orders</button>
        <button @click="filter = 'processing'" :class="filter === 'processing' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">Processing</button>
        <button @click="filter = 'completed'" :class="filter === 'completed' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">Completed</button>
        <button @click="filter = 'cancelled'" :class="filter === 'cancelled' ? 'text-white border-b-2 border-[var(--gold)]' : 'text-[var(--text-secondary)] hover:text-white'" class="pb-3 font-medium whitespace-nowrap">Cancelled</button>
    </div>

    <!-- Order List -->
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead>
                    <tr class="bg-[var(--surface)] text-[var(--text-muted)] text-sm">
                        <th class="px-6 py-4 font-medium">Order #</th>
                        <th class="px-6 py-4 font-medium">Date</th>
                        <th class="px-6 py-4 font-medium">Status</th>
                        <th class="px-6 py-4 font-medium text-right">Total</th>
                        <th class="px-6 py-4 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <template x-for="order in visibleOrders" :key="order.id">
                    <tr class="hover:bg-[var(--surface)]/50 transition-colors group cursor-pointer" @click="window.location='/account/orders/' + order.id">
                        <td class="px-6 py-4">
                            <span class="font-medium group-hover:text-[var(--gold)] transition-colors" x-text="order.id"></span>
                        </td>
                        <td class="px-6 py-4 text-[var(--text-secondary)]" x-text="order.date"></td>
                        <td class="px-6 py-4">
                            <span x-show="order.status === 'Completed'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">Completed</span>
                            <span x-show="order.status === 'Cancelled'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-500/10 text-red-400 border border-red-500/20">Cancelled</span>
                            <span x-show="order.status !== 'Completed' && order.status !== 'Cancelled'" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20" x-text="order.status"></span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium" x-text="order.total"></td>
                        <td class="px-6 py-4 text-center">
                            <a :href="'/account/orders/' + order.id" class="text-[var(--text-secondary)] hover:text-white transition-colors" @click.stop>
                                <i data-lucide="chevron-right" class="w-5 h-5 mx-auto"></i>
                            </a>
                        </td>
                    </tr>
                    </template>
                </tbody>
            </table>
            <div x-show="visibleOrders.length === 0" class="p-10 text-center text-[var(--text-muted)]">
                No orders match this filter.
            </div>
        </div>
    </div>
</div>

<script>
    function ordersTabs() {
        const orders = <?= json_encode(array_map(fn($o) => [
            'id' => $o['id'],
            'date' => date('M j, Y', strtotime($o['date'])),
            'status' => $o['status'],
            'status_key' => $o['status_key'],
            'total' => $o['total'],
        ], $orders), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?>;
        return {
            filter: 'all',
            search: '',
            orders: orders,
            get visibleOrders() {
                const q = this.search.trim().toLowerCase();
                return this.orders.filter(o => {
                    const matchesFilter = this.filter === 'all'
                        || o.status_key === this.filter
                        || (this.filter === 'processing' && (o.status_key === 'pending' || o.status_key === 'processing'));
                    const matchesSearch = q === '' || o.id.toLowerCase().includes(q);
                    return matchesFilter && matchesSearch;
                });
            }
        };
    }
</script>
