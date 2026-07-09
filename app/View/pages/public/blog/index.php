<!-- Blog Hero -->
<section class="pt-32 pb-16 px-6 lg:px-20 border-b border-[var(--border)] relative overflow-hidden">
    <!-- Abstract background glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[var(--gold)]/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-[1280px] mx-auto text-center relative z-10">
        <h1 class="text-4xl md:text-5xl font-bold mb-6">Insights & News</h1>
        <p class="text-[var(--text-secondary)] text-lg mb-0 max-w-2xl mx-auto">Discover the latest trends in premium corporate gifting, industry news, and guides to elevating your brand presence.</p>
    </div>
</section>

<!-- Blog Content Grid -->
<section class="py-20 px-6 lg:px-20">
    <div class="max-w-[1280px] mx-auto flex flex-col lg:flex-row gap-12">
        
        <!-- Main Content -->
        <div class="lg:w-3/4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php foreach ($posts as $index => $post): ?>
                    <article class="card group flex flex-col h-full bg-transparent border-0 rounded-none">
                        <a href="/blog/<?= $post['slug'] ?>" class="block overflow-hidden rounded-[16px] mb-6 relative aspect-[16/10]">
                            <img src="<?= $post['featured_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 transition-colors duration-500"></div>
                        </a>
                        <div class="flex-grow flex flex-col">
                            <div class="flex items-center gap-3 text-sm text-[var(--text-muted)] mb-3">
                                <span><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
                                <span class="w-1 h-1 rounded-full bg-[var(--border)]"></span>
                                <span><?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?></span>
                            </div>
                            <h2 class="text-2xl font-bold mb-3 group-hover:text-[var(--gold)] transition-colors line-clamp-2">
                                <a href="/blog/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                            </h2>
                            <p class="text-[var(--text-secondary)] line-clamp-3 mb-6 flex-grow">
                                <?= htmlspecialchars($post['excerpt']) ?>
                            </p>
                            <a href="/blog/<?= $post['slug'] ?>" class="inline-flex items-center gap-2 text-[var(--gold)] font-medium hover:text-white transition-colors mt-auto w-fit">
                                Read Article <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination Placeholder -->
            <div class="mt-16 pt-8 border-t border-[var(--border)] flex justify-center">
                <nav class="flex gap-2">
                    <button class="w-10 h-10 rounded-[8px] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-white hover:border-white transition-colors disabled:opacity-50" disabled>
                        <i data-lucide="chevron-left" class="w-5 h-5"></i>
                    </button>
                    <button class="w-10 h-10 rounded-[8px] bg-[var(--gold)] text-black font-medium flex items-center justify-center">1</button>
                    <button class="w-10 h-10 rounded-[8px] border border-[var(--border)] text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors flex items-center justify-center">2</button>
                    <button class="w-10 h-10 rounded-[8px] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors">
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="lg:w-1/4 space-y-12">
            <!-- Search -->
            <div>
                <h3 class="text-lg font-bold mb-4">Search</h3>
                <div class="relative">
                    <input type="text" placeholder="Search articles..." class="input-field w-full pl-4 pr-10">
                    <i data-lucide="search" class="absolute right-4 top-1/2 -translate-y-1/2 text-[var(--text-muted)] w-4 h-4"></i>
                </div>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="text-lg font-bold mb-4">Categories</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex justify-between"><span>Corporate Gifting</span> <span class="text-[var(--text-muted)]">12</span></a></li>
                    <li><a href="#" class="text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex justify-between"><span>Sustainability</span> <span class="text-[var(--text-muted)]">8</span></a></li>
                    <li><a href="#" class="text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex justify-between"><span>Case Studies</span> <span class="text-[var(--text-muted)]">5</span></a></li>
                    <li><a href="#" class="text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors flex justify-between"><span>Company News</span> <span class="text-[var(--text-muted)]">3</span></a></li>
                </ul>
            </div>

            <!-- Tags -->
            <div>
                <h3 class="text-lg font-bold mb-4">Popular Tags</h3>
                <div class="flex flex-wrap gap-2">
                    <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Premium</a>
                    <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Leather</a>
                    <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Tech</a>
                    <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Customization</a>
                    <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Employee Appreciation</a>
                </div>
            </div>
        </aside>

    </div>
</section>
