/**
 * Marigold Signature - GSAP Animation System
 * Core animation engine for liquid glass luxury theme
 */

document.addEventListener("DOMContentLoaded", (event) => {
    // Register GSAP Plugins
    if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
        gsap.registerPlugin(ScrollTrigger);
        
        // Respect prefers-reduced-motion
        const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

        if (!reducedMotion) {
            initPageTransitions();
            initScrollAnimations();
        }
    }
});

function initPageTransitions() {
    // Page load fade in + translate up
    gsap.fromTo("main", 
        { opacity: 0, y: 16 }, 
        { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }
    );
}

function initScrollAnimations() {
    // Reveal Cards with stagger
    const cards = gsap.utils.toArray('.product-card, .card');
    cards.forEach((card) => {
        gsap.from(card, {
            scrollTrigger: {
                trigger: card,
                start: "top bottom-=100",
                toggleActions: "play none none none"
            },
            y: 24,
            opacity: 0,
            duration: 0.6,
            ease: "power2.out"
        });
    });

    // Sticky Header Shrink & Blur
    const header = document.querySelector('header');
    if (header) {
        ScrollTrigger.create({
            start: "top -50",
            end: 99999,
            toggleClass: {className: "fluid-blur border-b border-[var(--border)]", targets: header}
        });
    }

    // Number Counters
    const counters = gsap.utils.toArray('.animated-counter');
    counters.forEach(counter => {
        let endValue = parseFloat(counter.getAttribute('data-value'));
        gsap.fromTo(counter, 
            { innerHTML: 0 }, 
            {
                innerHTML: endValue,
                duration: 2,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: counter,
                    start: "top bottom-=100",
                    toggleActions: "play none none none"
                },
                snap: { innerHTML: 1 },
                onUpdate: function() {
                    counter.innerHTML = Math.ceil(this.targets()[0].innerHTML);
                }
            }
        );
    });
}
