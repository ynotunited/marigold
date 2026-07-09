<!-- Reading Progress Bar -->
<div class="fixed top-0 left-0 w-full h-1 bg-[var(--surface)] z-50">
    <div class="h-full bg-[var(--gold)] w-0 transition-all duration-100 ease-out" id="reading-progress"></div>
</div>

<!-- Blog Hero -->
<section class="pt-32 pb-12 px-6 lg:px-20 relative">
    <div class="max-w-[800px] mx-auto text-center">
        <div class="flex items-center justify-center gap-3 text-sm text-[var(--text-muted)] mb-6">
            <span>Corporate Gifting</span>
            <span class="w-1 h-1 rounded-full bg-[var(--border)]"></span>
            <span><?= date('F j, Y', strtotime($post['published_at'])) ?></span>
            <span class="w-1 h-1 rounded-full bg-[var(--border)]"></span>
            <span>4 min read</span>
        </div>
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-8 leading-tight">
            <?= htmlspecialchars($post['title']) ?>
        </h1>
        <div class="flex items-center justify-center gap-4">
            <div class="w-12 h-12 rounded-full bg-[var(--surface)] flex items-center justify-center border border-[var(--border)]">
                <i data-lucide="user" class="w-6 h-6 text-[var(--text-secondary)]"></i>
            </div>
            <div class="text-left">
                <p class="font-medium"><?= htmlspecialchars($post['first_name'] . ' ' . $post['last_name']) ?></p>
                <p class="text-sm text-[var(--text-muted)]">Content Strategist</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Image -->
<section class="px-6 lg:px-20 mb-16">
    <div class="max-w-[1000px] mx-auto">
        <div class="rounded-[24px] overflow-hidden aspect-[16/9] relative">
            <img src="<?= $post['featured_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover">
        </div>
    </div>
</section>

<!-- Article Body -->
<section class="px-6 lg:px-20 pb-20">
    <div class="max-w-[800px] mx-auto">
        
        <div class="prose prose-invert prose-lg max-w-none prose-headings:font-manrope prose-headings:font-bold prose-h2:text-3xl prose-h2:mt-12 prose-h2:mb-6 prose-p:text-[var(--text-secondary)] prose-p:leading-relaxed prose-p:mb-6 prose-a:text-[var(--gold)] prose-blockquote:border-l-4 prose-blockquote:border-[var(--gold)] prose-blockquote:bg-[var(--surface)] prose-blockquote:p-6 prose-blockquote:rounded-r-[12px] prose-blockquote:text-xl prose-blockquote:font-medium prose-blockquote:italic prose-blockquote:my-10">
            <?= $post['content'] ?>
        </div>
        
        <!-- Tags & Share -->
        <div class="mt-16 pt-8 border-t border-[var(--border)] flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex flex-wrap items-center gap-2">
                <span class="text-[var(--text-muted)] mr-2">Tags:</span>
                <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Corporate Gifting</a>
                <a href="#" class="px-3 py-1 rounded-[6px] bg-[var(--surface)] border border-[var(--border)] text-sm text-[var(--text-secondary)] hover:border-[var(--gold)] hover:text-white transition-colors">Trends 2026</a>
            </div>
            
            <div class="flex items-center gap-4">
                <span class="text-[var(--text-muted)]">Share:</span>
                <button class="w-10 h-10 rounded-full bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors">
                    <i data-lucide="twitter" class="w-4 h-4"></i>
                </button>
                <button class="w-10 h-10 rounded-full bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors">
                    <i data-lucide="linkedin" class="w-4 h-4"></i>
                </button>
                <button class="w-10 h-10 rounded-full bg-[var(--surface)] border border-[var(--border)] flex items-center justify-center text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)] transition-colors">
                    <i data-lucide="link" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

    </div>
</section>

<!-- Related Articles -->
<section class="py-20 px-6 lg:px-20 bg-[var(--surface)]">
    <div class="max-w-[1280px] mx-auto">
        <div class="flex items-end justify-between mb-12">
            <h2 class="text-3xl font-bold">Related Articles</h2>
            <a href="/blog" class="text-[var(--gold)] font-medium hover:text-white transition-colors flex items-center gap-2">
                View All <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Mocked related post -->
            <article class="card group flex flex-col h-full bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
                <a href="#" class="block overflow-hidden relative aspect-[16/10]">
                    <img src="https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800&auto=format&fit=crop" alt="Related" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                </a>
                <div class="p-6 flex-grow flex flex-col">
                    <h3 class="text-xl font-bold mb-3 group-hover:text-[var(--gold)] transition-colors line-clamp-2">
                        <a href="#">The Psychology of Premium Merchandise</a>
                    </h3>
                    <p class="text-[var(--text-secondary)] line-clamp-2 text-sm flex-grow">
                        Why does a high-quality pen or a custom leather folio make such a lasting impact? We dive into the psychology...
                    </p>
                </div>
            </article>
            <!-- Mocked related post -->
            <article class="card group flex flex-col h-full bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
                <a href="#" class="block overflow-hidden relative aspect-[16/10]">
                    <img src="https://images.unsplash.com/photo-1610555356070-d1fb336f1ae8?q=80&w=800&auto=format&fit=crop" alt="Related" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                </a>
                <div class="p-6 flex-grow flex flex-col">
                    <h3 class="text-xl font-bold mb-3 group-hover:text-[var(--gold)] transition-colors line-clamp-2">
                        <a href="#">Sustainable Gifting: A Necessity, Not a Trend</a>
                    </h3>
                    <p class="text-[var(--text-secondary)] line-clamp-2 text-sm flex-grow">
                        Eco-friendly corporate merchandise is no longer just nice to have. Learn how to align your brand values...
                    </p>
                </div>
            </article>
        </div>
    </div>
</section>

<script>
    // Reading Progress Bar Script
    window.addEventListener('scroll', () => {
        const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (window.scrollY / docHeight) * 100;
        document.getElementById('reading-progress').style.width = scrolled + '%';
    });
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
