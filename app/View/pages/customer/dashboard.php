<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-3xl font-bold mb-2"><?= $greeting ?>, <?= htmlspecialchars($user['first_name']) ?></h1>
        <p class="text-[var(--text-secondary)]">Here is what's happening with your account today.</p>
    </div>
    
    <!-- Action buttons (Desktop) -->
    <div class="hidden md:flex items-center gap-4">
        <a href="/shop" class="btn btn-primary h-[44px] px-6">New Order</a>
        <a href="/quotes/new" class="btn btn-secondary border border-[var(--border)] h-[44px] px-6">Request Quote</a>
    </div>
</div>

<!-- Overview Stats (Grid) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
    <!-- Stat 1 -->
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors cursor-pointer group">
        <div class="w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center mb-4 group-hover:bg-[var(--gold)]/10 transition-colors">
            <i data-lucide="package" class="w-5 h-5 text-[var(--gold)]"></i>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mb-1">Pending Orders</p>
        <h3 class="text-3xl font-bold font-manrope"><?= $stats['pending_orders'] ?></h3>
    </div>
    
    <!-- Stat 2 -->
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors cursor-pointer group">
        <div class="w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center mb-4 group-hover:bg-[var(--gold)]/10 transition-colors">
            <i data-lucide="check-circle" class="w-5 h-5 text-[var(--gold)]"></i>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mb-1">Completed Orders</p>
        <h3 class="text-3xl font-bold font-manrope"><?= $stats['completed_orders'] ?></h3>
    </div>
    
    <!-- Stat 3 -->
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors cursor-pointer group relative overflow-hidden">
        <div class="absolute top-0 right-0 w-1 h-full bg-[var(--gold)]"></div>
        <div class="w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center mb-4 group-hover:bg-[var(--gold)]/10 transition-colors">
            <i data-lucide="file-text" class="w-5 h-5 text-[var(--gold)]"></i>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mb-1">Action Required</p>
        <h3 class="text-3xl font-bold font-manrope"><?= $stats['pending_quotes'] ?> <span class="text-lg font-normal text-[var(--text-muted)]">Quotes</span></h3>
    </div>
    
    <!-- Stat 4 -->
    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 hover:border-[var(--gold)]/50 transition-colors cursor-pointer group">
        <div class="w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center mb-4 group-hover:bg-[var(--gold)]/10 transition-colors">
            <i data-lucide="heart" class="w-5 h-5 text-[var(--gold)]"></i>
        </div>
        <p class="text-sm text-[var(--text-secondary)] mb-1">Wishlist</p>
        <h3 class="text-3xl font-bold font-manrope"><?= $stats['wishlist_count'] ?></h3>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-10">
    
    <!-- Left Column: Tables (Takes up 2 cols on XL) -->
    <div class="xl:col-span-2 space-y-8">
        
        <!-- Recent Orders -->
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)] flex items-center justify-between">
                <h2 class="text-xl font-bold font-manrope">Recent Orders</h2>
                <a href="/account/orders" class="text-sm text-[var(--gold)] hover:text-white transition-colors">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-[var(--surface)] text-[var(--text-muted)] text-sm">
                            <th class="px-6 py-4 font-medium">Order #</th>
                            <th class="px-6 py-4 font-medium">Date</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        <?php foreach($recent_orders as $order): ?>
                        <tr class="hover:bg-[var(--surface)]/50 transition-colors group cursor-pointer">
                            <td class="px-6 py-4">
                                <span class="font-medium group-hover:text-[var(--gold)] transition-colors"><?= $order['order_number'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-[var(--text-secondary)]"><?= date('M j, Y', strtotime($order['date'])) ?></td>
                            <td class="px-6 py-4">
                                <?php if($order['status'] === 'Completed'): ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-500/10 text-green-400 border border-green-500/20">
                                        Completed
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                                        <?= $order['status'] ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right font-medium"><?= $order['total'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pending Quotes -->
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <h2 class="text-xl font-bold font-manrope">Pending Quotes</h2>
                    <span class="bg-[var(--gold)] text-[#111] text-xs font-bold px-2 py-0.5 rounded-full"><?= count($pending_quotes) ?></span>
                </div>
                <a href="/account/quotes" class="text-sm text-[var(--gold)] hover:text-white transition-colors">View All</a>
            </div>
            
            <div class="divide-y divide-[var(--border)]">
                <?php foreach($pending_quotes as $quote): ?>
                <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-[var(--surface)]/50 transition-colors">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h3 class="font-medium text-lg text-[var(--gold)]"><?= $quote['quote_number'] ?></h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[var(--surface)] border border-[var(--border)] text-[var(--text-secondary)]">
                                <?= $quote['items'] ?> Items
                            </span>
                        </div>
                        <p class="text-sm text-[var(--text-muted)]">Requested on <?= date('M j, Y', strtotime($quote['date'])) ?></p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-[var(--text-secondary)] mr-2"><?= $quote['status'] ?></span>
                        <a href="/account/quotes/<?= $quote['quote_number'] ?>" class="btn btn-secondary border border-[var(--border)] px-4 py-2 text-sm h-auto">Review</a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
    
    <!-- Right Column: Sidebar -->
    <div class="xl:col-span-1">
        
        <!-- Account Manager -->
        <div class="bg-[var(--surface)] rounded-[16px] p-6 mb-8 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-[var(--gold)]/5 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-[var(--gold)]/10 transition-colors"></div>
            
            <h3 class="text-lg font-bold font-manrope mb-4 relative z-10">Your Account Manager</h3>
            <div class="flex items-center gap-4 mb-6 relative z-10">
                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=150&auto=format&fit=crop" alt="Sarah Jenkins" class="w-14 h-14 rounded-full object-cover border-2 border-[var(--border)]">
                <div>
                    <p class="font-medium">Sarah Jenkins</p>
                    <p class="text-sm text-[var(--text-muted)]">sarah@marigoldsignature.com</p>
                </div>
            </div>
            <a href="mailto:sarah@marigoldsignature.com" class="btn btn-secondary border border-[var(--border)] w-full relative z-10 bg-[var(--bg-primary)]">Contact Sarah</a>
        </div>

        <!-- Mobile Carousel: Recommended Products -->
        <h3 class="text-lg font-bold font-manrope mb-4">Recommended for You</h3>
        
        <!-- Swiper Container (Hidden scrollbar on desktop, swipe on mobile) -->
        <div class="swiper mobile-carousel -mx-6 px-6 lg:mx-0 lg:px-0">
            <div class="swiper-wrapper">
                <?php foreach($recommended_products as $product): ?>
                <div class="swiper-slide w-[240px] lg:w-full lg:mb-4">
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[12px] p-3 flex flex-col lg:flex-row gap-4 group cursor-pointer hover:border-[var(--gold)] transition-colors h-full">
                        <div class="w-full lg:w-24 aspect-square lg:aspect-auto lg:h-24 rounded-[8px] overflow-hidden relative shrink-0">
                            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <?php if($product['badge']): ?>
                                <span class="absolute top-2 left-2 text-[10px] uppercase font-bold bg-[var(--gold)] text-[#111] px-2 py-0.5 rounded-[4px]"><?= $product['badge'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col justify-center py-1">
                            <h4 class="font-medium text-sm lg:text-base line-clamp-2 mb-1 group-hover:text-[var(--gold)] transition-colors"><?= htmlspecialchars($product['name']) ?></h4>
                            <p class="text-[var(--text-muted)] text-sm mt-auto"><?= $product['price'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>
</div>
