<?php // app/View/pages/public/solutions.php ?>

<div class="pt-24 pb-0 bg-[var(--bg-primary)] overflow-hidden">
    
    <!-- Hero -->
    <section class="py-20 sm:py-28 text-center border-b border-[var(--border)] relative">
        <div class="absolute inset-0 bg-gradient-to-b from-[var(--surface)] to-[var(--bg-primary)] -z-10"></div>
        <div class="container mx-auto px-4 max-w-4xl">
            <span class="text-[var(--gold)] text-sm font-bold tracking-widest uppercase mb-4 block">B2B Services</span>
            <h1 class="font-['Manrope'] text-4xl sm:text-6xl font-extrabold leading-tight mb-6 text-white">Bespoke Corporate Gifting Solutions</h1>
            <p class="text-[var(--text-secondary)] text-lg sm:text-xl leading-relaxed mb-10 max-w-2xl mx-auto">
                Comprehensive, end-to-end merchandise programs designed to elevate your brand, engage your employees, and impress your clients.
            </p>
            <a href="/quote" class="inline-flex items-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:bg-[#D4AF37] transition-all hover:-translate-y-1">
                Start a Project <i data-lucide="arrow-right" class="w-5 h-5"></i>
            </a>
        </div>
    </section>

    <!-- Solutions Grid -->
    <section class="py-20 sm:py-28 bg-[var(--bg-primary)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <?php
                $solutions = [
                    ['icon' => 'gift', 'title' => 'Employee Welcome Kits', 'desc' => 'Make day one memorable. We curate, brand, and assemble premium onboarding boxes that make new hires feel instantly valued and connected to your company culture.', 'link' => 'onboarding'],
                    ['icon' => 'crown', 'title' => 'Executive & VIP Gifting', 'desc' => 'For those moments when standard won\'t do. Access our exclusive catalogue of luxury items, from Montblanc pens to bespoke leather goods, perfect for C-suite clients.', 'link' => 'executive'],
                    ['icon' => 'presentation', 'title' => 'Event & Conference Swag', 'desc' => 'Stand out on the exhibition floor. We provide high-quality, memorable items that delegates actually want to keep, driving long-term brand recall long after the event ends.', 'link' => 'events'],
                    ['icon' => 'trophy', 'title' => 'Awards & Recognition', 'desc' => 'Celebrate milestones and outstanding performance with custom-designed acrylic, crystal, or wood awards, paired with premium congratulatory gift sets.', 'link' => 'awards'],
                ];
                foreach ($solutions as $sol): ?>
                    <div class="bg-[var(--surface)] border border-[var(--border)] p-8 sm:p-12 rounded-3xl group hover:border-[var(--gold)]/50 transition-colors">
                        <div class="w-16 h-16 rounded-2xl bg-[var(--gold)]/10 border border-[var(--gold)]/20 flex items-center justify-center mb-8 group-hover:bg-[var(--gold)] transition-colors">
                            <i data-lucide="<?= $sol['icon'] ?>" class="w-8 h-8 text-[var(--gold)] group-hover:text-black transition-colors"></i>
                        </div>
                        <h2 class="font-['Manrope'] text-2xl font-bold mb-4 text-white"><?= htmlspecialchars($sol['title']) ?></h2>
                        <p class="text-[var(--text-secondary)] leading-relaxed mb-8"><?= htmlspecialchars($sol['desc']) ?></p>
                        <a href="/quote?type=<?= $sol['link'] ?>" class="inline-flex items-center gap-2 text-[var(--gold)] font-bold hover:gap-3 transition-all">
                            Request a Quote <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- How It Works (Timeline) -->
    <section class="py-20 sm:py-28 bg-[var(--surface)] border-y border-[var(--border)]">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-20">
                <span class="text-[var(--gold)] text-sm font-bold tracking-widest uppercase mb-4 block">Process</span>
                <h2 class="font-['Manrope'] text-4xl sm:text-5xl font-extrabold mb-6 text-white">How It Works</h2>
                <p class="text-[var(--text-secondary)]">A streamlined, stress-free process from initial concept to final delivery.</p>
            </div>

            <div class="relative max-w-5xl mx-auto">
                <!-- Desktop Line -->
                <div class="hidden md:block absolute top-12 left-0 right-0 h-0.5 bg-[var(--border)] -z-10"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-6">
                    <!-- Step 1 -->
                    <div class="relative text-center">
                        <div class="w-24 h-24 mx-auto bg-[var(--bg-primary)] border-2 border-[var(--gold)] rounded-full flex items-center justify-center mb-6 shadow-[0_0_20px_rgba(201,162,39,0.2)]">
                            <i data-lucide="message-square" class="w-10 h-10 text-[var(--gold)]"></i>
                        </div>
                        <h3 class="font-['Manrope'] font-bold text-xl mb-3 text-white">1. Consultation</h3>
                        <p class="text-[var(--text-secondary)] text-sm leading-relaxed">We discuss your goals, budget, audience, and timeline to understand your exact needs.</p>
                    </div>
                    <!-- Step 2 -->
                    <div class="relative text-center">
                        <div class="w-24 h-24 mx-auto bg-[var(--bg-primary)] border-2 border-[var(--border)] rounded-full flex items-center justify-center mb-6">
                            <i data-lucide="pen-tool" class="w-10 h-10 text-white"></i>
                        </div>
                        <h3 class="font-['Manrope'] font-bold text-xl mb-3 text-white">2. Curation & Design</h3>
                        <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Our team creates a custom proposal with product recommendations and digital mockups.</p>
                    </div>
                    <!-- Step 3 -->
                    <div class="relative text-center">
                        <div class="w-24 h-24 mx-auto bg-[var(--bg-primary)] border-2 border-[var(--border)] rounded-full flex items-center justify-center mb-6">
                            <i data-lucide="package-check" class="w-10 h-10 text-white"></i>
                        </div>
                        <h3 class="font-['Manrope'] font-bold text-xl mb-3 text-white">3. Production</h3>
                        <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Upon approval, we move to production, ensuring strict quality control during branding.</p>
                    </div>
                    <!-- Step 4 -->
                    <div class="relative text-center">
                        <div class="w-24 h-24 mx-auto bg-[var(--bg-primary)] border-2 border-[var(--border)] rounded-full flex items-center justify-center mb-6">
                            <i data-lucide="truck" class="w-10 h-10 text-white"></i>
                        </div>
                        <h3 class="font-['Manrope'] font-bold text-xl mb-3 text-white">4. Delivery</h3>
                        <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Securely packaged and delivered to your office or directly to individual recipients nationwide.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Industries Served -->
    <section class="py-20 sm:py-28">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="font-['Manrope'] text-3xl sm:text-4xl font-extrabold mb-6 text-white">Industries We Serve</h2>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php
                $industries = [
                    ['name' => 'Banking & Finance', 'icon' => 'landmark'],
                    ['name' => 'Oil & Gas', 'icon' => 'droplet'],
                    ['name' => 'Technology', 'icon' => 'cpu'],
                    ['name' => 'Telecommunications', 'icon' => 'smartphone'],
                    ['name' => 'FMCG', 'icon' => 'shopping-cart'],
                    ['name' => 'Real Estate', 'icon' => 'building-2'],
                ];
                foreach ($industries as $ind): ?>
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-2xl p-6 text-center hover:border-[var(--gold)] transition-colors group cursor-default">
                        <i data-lucide="<?= $ind['icon'] ?>" class="w-8 h-8 mx-auto mb-4 text-[var(--text-muted)] group-hover:text-[var(--gold)] transition-colors"></i>
                        <h3 class="text-sm font-bold text-white"><?= $ind['name'] ?></h3>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</div>
