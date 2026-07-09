<?php // app/View/pages/public/contact.php ?>

<div class="pt-24 pb-0 bg-[var(--bg-primary)]">

    <!-- Header -->
    <section class="py-20 sm:py-28 text-center border-b border-[var(--border)] relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-[var(--surface)] to-[var(--bg-primary)] -z-10"></div>
        <div class="container mx-auto px-4 max-w-4xl">
            <span class="text-[var(--gold)] text-sm font-bold tracking-widest uppercase mb-4 block">Get In Touch</span>
            <h1 class="font-['Manrope'] text-4xl sm:text-6xl font-extrabold leading-tight mb-6 text-white">Let's Discuss Your Project</h1>
            <p class="text-[var(--text-secondary)] text-lg sm:text-xl leading-relaxed max-w-2xl mx-auto">
                Whether you need a quick quote for an upcoming event or want to discuss a comprehensive annual gifting program, our team is ready to help.
            </p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-20 sm:py-28" x-data="contactForm()">
        <div class="container mx-auto px-4 sm:px-8 max-w-[1440px]">
            <div class="flex flex-col lg:flex-row gap-12">

                <!-- Contact Info + Map (Left) -->
                <div class="w-full lg:w-5/12 space-y-6">

                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-10">
                        <h3 class="font-['Manrope'] text-2xl font-bold mb-8 text-white">Contact Information</h3>

                        <div class="space-y-6">

                            <!-- Address -->
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center shrink-0 group-hover:border-[var(--gold)] transition-colors">
                                    <i data-lucide="map-pin" class="w-5 h-5 text-[var(--gold)]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Head Office</h4>
                                    <a href="https://maps.google.com/?q=6+Oluwole+Omole+St,+Opebi,+Lagos+101233,+Nigeria" target="_blank" rel="noopener noreferrer" class="text-[var(--text-secondary)] text-sm leading-relaxed hover:text-[var(--gold)] transition-colors">
                                        6 Oluwole Omole Street<br>Opebi, Lagos 101233<br>Lagos, Nigeria
                                    </a>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center shrink-0 group-hover:border-[var(--gold)] transition-colors">
                                    <i data-lucide="phone" class="w-5 h-5 text-[var(--gold)]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Phone & WhatsApp</h4>
                                    <a href="tel:+2348130270391" class="text-[var(--text-secondary)] text-sm hover:text-[var(--gold)] transition-colors block">+234 813 027 0391</a>
                                    <a href="https://wa.me/2348130270391" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 text-[#25D366] text-sm mt-1 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                        Chat on WhatsApp
                                    </a>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center shrink-0 group-hover:border-[var(--gold)] transition-colors">
                                    <i data-lucide="mail" class="w-5 h-5 text-[var(--gold)]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Email</h4>
                                    <a href="mailto:info@marigoldsignatureng.com" class="text-[var(--text-secondary)] text-sm hover:text-[var(--gold)] transition-colors">info@marigoldsignatureng.com</a>
                                </div>
                            </div>

                            <!-- Business Hours -->
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center shrink-0 group-hover:border-[var(--gold)] transition-colors">
                                    <i data-lucide="clock" class="w-5 h-5 text-[var(--gold)]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-1">Business Hours</h4>
                                    <p class="text-[var(--text-secondary)] text-sm leading-relaxed">Monday – Friday: 8:00 AM – 6:00 PM<br>Saturday: 10:00 AM – 2:00 PM</p>
                                </div>
                            </div>

                            <!-- Socials -->
                            <div class="flex items-start gap-4 group">
                                <div class="w-12 h-12 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center shrink-0 group-hover:border-[var(--gold)] transition-colors">
                                    <i data-lucide="share-2" class="w-5 h-5 text-[var(--gold)]"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white mb-3">Follow Us</h4>
                                    <div class="flex gap-3">
                                        <a href="https://www.instagram.com/marigoldsignature_ng" target="_blank" rel="noopener noreferrer"
                                           class="w-9 h-9 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--gold)] hover:border-[var(--gold)] transition-colors">
                                            <i data-lucide="instagram" class="w-4 h-4"></i>
                                        </a>
                                        <a href="https://www.facebook.com/marigoldsignature_ng" target="_blank" rel="noopener noreferrer"
                                           class="w-9 h-9 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--gold)] hover:border-[var(--gold)] transition-colors">
                                            <i data-lucide="facebook" class="w-4 h-4"></i>
                                        </a>
                                        <a href="https://www.linkedin.com/company/marigold-signature" target="_blank" rel="noopener noreferrer"
                                           class="w-9 h-9 rounded-full bg-[var(--card)] border border-[var(--border)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--gold)] hover:border-[var(--gold)] transition-colors">
                                            <i data-lucide="linkedin" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Google Maps Embed -->
                    <div class="rounded-3xl overflow-hidden border border-[var(--border)]" style="height: 320px;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.4075883587!2d3.3565!3d6.5659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b922b51dcb2e9%3A0x5e1e0f7ef0c1e1b5!2s6%20Oluwole%20Omole%20St%2C%20Opebi%2C%20Lagos%20101233%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1720000000000!5m2!1sen!2sng"
                            width="100%"
                            height="100%"
                            style="border:0; filter: invert(90%) hue-rotate(180deg);"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Marigold Signature Location - 6 Oluwole Omole St, Opebi, Lagos">
                        </iframe>
                    </div>

                </div>

                <!-- Contact Form (Right) -->
                <div class="w-full lg:w-7/12">
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-3xl p-8 sm:p-12">
                        <h3 class="font-['Manrope'] text-2xl font-bold mb-2 text-white">Send us a Message</h3>
                        <p class="text-[var(--text-secondary)] text-sm mb-8">We typically respond within 1 business day.</p>

                        <form @submit.prevent="submitForm">

                            <!-- Honeypot -->
                            <div style="display:none;">
                                <input type="text" name="website_url" tabindex="-1" autocomplete="off">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">First Name *</label>
                                    <input type="text" required placeholder="John" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-white placeholder-[var(--text-muted)] transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Last Name *</label>
                                    <input type="text" required placeholder="Doe" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-white placeholder-[var(--text-muted)] transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Email Address *</label>
                                    <input type="email" required placeholder="john@company.com" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-white placeholder-[var(--text-muted)] transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Phone Number</label>
                                    <input type="tel" placeholder="+234 800 000 0000" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-white placeholder-[var(--text-muted)] transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Company / Organization</label>
                                    <input type="text" placeholder="Your company name" class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-white placeholder-[var(--text-muted)] transition-colors">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Subject *</label>
                                    <select required class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-[var(--text-secondary)] appearance-none transition-colors">
                                        <option value="" disabled selected>Select a subject</option>
                                        <option value="quote">Request a Quote</option>
                                        <option value="corporate">Corporate Gifting Program</option>
                                        <option value="event">Event Merchandise</option>
                                        <option value="partnership">Partnership Inquiry</option>
                                        <option value="support">General Support</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-[var(--text-muted)] uppercase tracking-wide mb-2">Message *</label>
                                    <textarea required rows="5" placeholder="Tell us about your project, budget, timeline, and quantity requirements..." class="w-full bg-[var(--card)] border border-[var(--border)] rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:border-[var(--gold)] text-white placeholder-[var(--text-muted)] resize-none transition-colors"></textarea>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                <p class="text-xs text-[var(--text-muted)]">
                                    By submitting you agree to our <a href="/privacy-policy" class="text-[var(--gold)] hover:underline">Privacy Policy</a>.
                                </p>
                                <button type="submit" :disabled="submitting"
                                    class="inline-flex items-center gap-2 bg-[var(--gold)] text-black font-bold px-8 py-4 rounded-xl hover:bg-[#D4AF37] transition-all disabled:opacity-50 disabled:cursor-not-allowed shrink-0">
                                    <span x-show="!submitting" class="flex items-center gap-2">Send Message <i data-lucide="send" class="w-4 h-4"></i></span>
                                    <span x-show="submitting" class="flex items-center gap-2">Sending… <i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i></span>
                                </button>
                            </div>

                        </form>
                    </div>

                    <!-- Quick Contact Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                        <a href="tel:+2348130270391"
                           class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 flex items-center gap-4 hover:border-[var(--gold)] transition-colors group">
                            <div class="w-12 h-12 rounded-full bg-[var(--gold)]/10 flex items-center justify-center shrink-0 group-hover:bg-[var(--gold)] transition-colors">
                                <i data-lucide="phone" class="w-5 h-5 text-[var(--gold)] group-hover:text-black transition-colors"></i>
                            </div>
                            <div>
                                <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-0.5">Call Us</p>
                                <p class="text-white font-bold text-sm">+234 813 027 0391</p>
                            </div>
                        </a>
                        <a href="https://wa.me/2348130270391" target="_blank" rel="noopener noreferrer"
                           class="bg-[var(--surface)] border border-[var(--border)] rounded-2xl p-6 flex items-center gap-4 hover:border-[#25D366] transition-colors group">
                            <div class="w-12 h-12 rounded-full bg-[#25D366]/10 flex items-center justify-center shrink-0 group-hover:bg-[#25D366] transition-colors">
                                <svg class="w-5 h-5 text-[#25D366] group-hover:text-white transition-colors" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-0.5">WhatsApp</p>
                                <p class="text-white font-bold text-sm">Chat with us now</p>
                            </div>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

<!-- Floating WhatsApp Button (mobile) -->
<a href="https://wa.me/2348130270391" target="_blank" rel="noopener noreferrer"
   class="sm:hidden fixed bottom-24 right-4 w-14 h-14 bg-[#25D366] text-white rounded-full flex items-center justify-center shadow-xl z-40 hover:scale-110 transition-transform">
    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
</a>

<script>
function contactForm() {
    return {
        submitting: false,
        submitForm() {
            this.submitting = true;
            setTimeout(() => {
                this.submitting = false;
                this.$el.querySelector('form').reset();
                window.dispatchEvent(new CustomEvent('toast', { detail: { message: 'Message sent! We\'ll get back to you within 1 business day.', type: 'success' }}));
            }, 1500);
        }
    }
}
</script>
