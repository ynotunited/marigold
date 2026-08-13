<div style="background: var(--ivory); color: var(--ink);">
<!-- Reading Progress Bar -->
<div class="article-progress"><span id="reading-progress"></span></div>

<!-- Article Hero -->
<section class="page-hero">
    <div class="container">
        <div class="crumbs">Home <span>/</span> Blog <span>/</span> <?= htmlspecialchars($post['title']) ?></div>
        <div class="eyebrow center reveal">Marigold Journal</div>
        <h1 class="display reveal"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="article-hero-meta reveal">
            <span class="cat">Corporate Gifting</span>
            <span class="dot"></span>
            <span><?= date('F j, Y', strtotime($post['published_at'])) ?></span>
            <span class="dot"></span>
            <span>4 min read</span>
        </div>
        <div class="article-author reveal">
            <span class="author-ava"><?= strtoupper(substr($post['first_name'], 0, 1) . substr($post['last_name'], 0, 1)) ?></span>
            <div>
                <div class="author-name"><?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?></div>
                <div class="author-role">Content Strategist</div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Image -->
<section class="section">
    <div class="container" style="max-width: 960px;">
        <div class="article-featured">
            <img src="<?= $post['featured_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>">
        </div>
    </div>
</section>

<!-- Article Body -->
<section class="section" style="padding-top: 0;">
    <div class="container article-body" style="max-width: 780px;">
        <?= $post['content'] ?>

        <!-- Tags & Share -->
        <div class="article-foot">
            <div class="tag-list">
                <a href="<?= app_url('/blog') ?>">Corporate Gifting</a>
                <a href="<?= app_url('/blog') ?>">Trends 2026</a>
            </div>

            <div class="article-share">
                <span>Share</span>
                <button class="share-btn" aria-label="Share on X">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M18.9 2H22l-6.8 7.8L23.4 22h-6.3l-4.9-6.4L6.6 22H3.4l7.3-8.3L2.6 2h6.4l4.4 5.9L18.9 2zm-1.1 18h1.7L7.9 3.9H6.1L17.8 20z"/></svg>
                </button>
                <button class="share-btn" aria-label="Share on LinkedIn">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.03-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.41v1.56h.05c.47-.9 1.63-1.85 3.36-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.12 2.06 2.06 0 0 1 0 4.12zM7.12 20.45H3.55V9h3.57v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.55C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.73V1.72C24 .77 23.2 0 22.22 0z"/></svg>
                </button>
                <button class="share-btn" aria-label="Copy link">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Related Articles -->
<section class="section" style="background: var(--cream); border-top: 1px solid var(--line);">
    <div class="container">
        <div class="section-head">
            <div>
                <div class="eyebrow">Keep Reading</div>
                <h2 class="h2">Related <span class="gold-text">Articles</span></h2>
            </div>
            <a href="<?= app_url('/blog') ?>" class="btn btn-ghost">View All</a>
        </div>

        <div class="blog-grid">
            <article class="blog-card reveal">
                <a href="<?= app_url('/blog/psychology-premium-merchandise') ?>" class="bc-img">
                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800&auto=format&fit=crop" alt="The Psychology of Premium Merchandise">
                </a>
                <div class="bc-body">
                    <div class="bc-meta"><span class="cat">Insights</span><span class="dot"></span><span>Read</span></div>
                    <h3><a href="<?= app_url('/blog/psychology-premium-merchandise') ?>">The Psychology of Premium Merchandise</a></h3>
                    <p>Why does a high-quality pen or a custom leather folio make such a lasting impact? We dive into the psychology...</p>
                    <a href="<?= app_url('/blog/psychology-premium-merchandise') ?>" class="read">Read Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
                </div>
            </article>
            <article class="blog-card reveal">
                <a href="<?= app_url('/blog/sustainable-gifting-necessity') ?>" class="bc-img">
                    <img src="https://images.unsplash.com/photo-1610555356070-d1fb336f1ae8?q=80&w=800&auto=format&fit=crop" alt="Sustainable Gifting: A Necessity, Not a Trend">
                </a>
                <div class="bc-body">
                    <div class="bc-meta"><span class="cat">Sustainability</span><span class="dot"></span><span>Read</span></div>
                    <h3><a href="<?= app_url('/blog/sustainable-gifting-necessity') ?>">Sustainable Gifting: A Necessity, Not a Trend</a></h3>
                    <p>Eco-friendly corporate merchandise is no longer just nice to have. Learn how to align your brand values...</p>
                    <a href="<?= app_url('/blog/sustainable-gifting-necessity') ?>" class="read">Read Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
                </div>
            </article>
            <article class="blog-card reveal">
                <a href="<?= app_url('/blog/psychology-premium-merchandise') ?>" class="bc-img">
                    <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=800&auto=format&fit=crop" alt="Personalisation at Scale">
                </a>
                <div class="bc-body">
                    <div class="bc-meta"><span class="cat">Guides</span><span class="dot"></span><span>Read</span></div>
                    <h3><a href="<?= app_url('/blog/psychology-premium-merchandise') ?>">Personalisation at Scale: The New Standard</a></h3>
                    <p>Advancements in customization technology mean personalization is no longer restricted to just adding a logo...</p>
                    <a href="<?= app_url('/blog/psychology-premium-merchandise') ?>" class="read">Read Article <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg></a>
                </div>
            </article>
        </div>
    </div>
</section>
</div>

<script>
    // Reading Progress Bar Script
    window.addEventListener('scroll', () => {
        const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.scrollY / docHeight) * 100;
        document.getElementById('reading-progress').style.width = scrolled + '%';
    }, { passive: true });
</script>

<!-- Schema.org Article -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": "<?= htmlspecialchars($post['title']) ?>",
  "image": [
    "<?= $post['featured_image'] ?>"
   ],
  "datePublished": "<?= date('c', strtotime($post['published_at'])) ?>",
  "author": [{
      "@type": "Person",
      "name": "<?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?>"
  }]
}
</script>
