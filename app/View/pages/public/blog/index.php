<div style="background: var(--ivory); color: var(--ink);">
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home <span>/</span> Blog</div>
        <div class="eyebrow center reveal">Marigold Journal</div>
        <h1 class="display reveal">Insights <span class="gold-text">&amp; News</span></h1>
        <p class="lead reveal">Discover the latest trends in premium corporate gifting, industry news, and guides to elevating your brand presence.</p>
    </div>
</section>

<section class="section">
    <div class="container blog-layout">
        <!-- Main Content -->
        <div>
            <div class="blog-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="blog-card reveal">
                        <a href="/blog/<?= $post['slug'] ?>" class="bc-img">
                            <img src="<?= $post['featured_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
                        </a>
                        <div class="bc-body">
                            <div class="bc-meta">
                                <span class="cat">Corporate Gifting</span>
                                <span class="dot"></span>
                                <span><?= date('M j, Y', strtotime($post['published_at'])) ?></span>
                            </div>
                            <h3><a href="/blog/<?= $post['slug'] ?>"><?= htmlspecialchars($post['title']) ?></a></h3>
                            <p><?= htmlspecialchars($post['excerpt']) ?></p>
                            <a href="/blog/<?= $post['slug'] ?>" class="read">
                                Read Article
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="shop-pagination">
                <button class="pg-btn" disabled>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <button class="pg-btn active">1</button>
                <button class="pg-btn">2</button>
                <button class="pg-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>

        <!-- Sidebar -->
        <aside class="blog-side">
            <div class="side-card side-search">
                <div class="side-h">Search</div>
                <form action="/search" method="get" class="field">
                    <input type="text" name="q" placeholder="Search articles..." aria-label="Search articles">
                    <button type="submit" aria-label="Search">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>
                </form>
            </div>

            <div class="side-card">
                <div class="side-h">Categories</div>
                <div class="side-list">
                    <a href="/blog"><span>All Articles</span></a>
                    <a href="/blog"><span>Corporate Gifting</span> <span>12</span></a>
                    <a href="/blog"><span>Sustainability</span> <span>8</span></a>
                    <a href="/blog"><span>Case Studies</span> <span>5</span></a>
                    <a href="/blog"><span>Company News</span> <span>3</span></a>
                </div>
            </div>

            <div class="side-card">
                <div class="side-h">Popular Tags</div>
                <div class="tag-list">
                    <a href="/blog">Premium</a>
                    <a href="/blog">Leather</a>
                    <a href="/blog">Tech</a>
                    <a href="/blog">Customization</a>
                    <a href="/blog">Employee Appreciation</a>
                </div>
            </div>
        </aside>
    </div>
</section>
</div>
