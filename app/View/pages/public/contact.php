<?php // app/View/pages/public/contact.php ?>

<div style="background: var(--ivory); color: var(--ink);">

    <section class="page-hero">
        <div class="container">
            <div class="crumbs"><a href="/">Home</a><span>/</span><span>Contact</span></div>
            <span class="eyebrow reveal">Start a Conversation</span>
            <h1 class="display h1 reveal">Let's make your brand <span class="gold-text">unforgettable</span></h1>
            <p class="lead reveal">Tell us about your programme — our team responds within one business day with a tailored proposal.</p>
        </div>
    </section>

    <section class="section" style="padding-top:20px">
        <div class="container contact-wrap">
            <div class="reveal">
                <div class="info-card">
                    <span class="ic-ico"><i data-lucide="map-pin"></i></span>
                    <div><strong>Head Office</strong><p>6 Oluwole Omole Street, Opebi, Lagos 101233, Nigeria</p></div>
                </div>
                <div class="info-card">
                    <span class="ic-ico"><i data-lucide="phone"></i></span>
                    <div><strong>Call Us</strong><p><a class="ct-link" href="tel:+2348130270391">+234 813 027 0391</a></p></div>
                </div>
                <div class="info-card">
                    <span class="ic-ico ic-wa"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg></span>
                    <div><strong>WhatsApp</strong>
                        <a class="btn btn-whatsapp" style="margin-top:12px;padding:10px 18px;font-size:13px" href="https://wa.me/2348130270391" target="_blank" rel="noopener">Chat on WhatsApp</a>
                    </div>
                </div>
                <div class="info-card">
                    <span class="ic-ico"><i data-lucide="mail"></i></span>
                    <div><strong>Email</strong><p>info@marigoldsignatureng.com</p></div>
                </div>
                <div class="info-card">
                    <span class="ic-ico"><i data-lucide="clock"></i></span>
                    <div><strong>Business Hours</strong><p>Monday – Friday: 8:00 AM – 6:00 PM<br>Saturday: 10:00 AM – 2:00 PM</p></div>
                </div>
                <div class="dark" style="border-radius:var(--radius);padding:26px">
                    <div class="foot-h" style="margin-bottom:10px">Bulk &amp; corporate enquiries</div>
                    <p style="font-size:13.5px;color:rgba(253,250,242,.7)">For orders above 50 units or gifting programmes, a dedicated account manager will be assigned to your organisation.</p>
                </div>
            </div>

            <div class="reveal d1">
                <form class="form-grid" id="contactForm" novalidate>
                    <div class="form-row full">
                        <label for="ctName">Full name <em>*</em></label>
                        <input class="field" id="ctName" required placeholder="Your name">
                    </div>
                    <div class="form-row">
                        <label for="ctCompany">Company <em>*</em></label>
                        <input class="field" id="ctCompany" required placeholder="Organisation">
                    </div>
                    <div class="form-row">
                        <label for="ctEmail">Work email <em>*</em></label>
                        <input class="field" id="ctEmail" type="email" required placeholder="you@company.com">
                    </div>
                    <div class="form-row">
                        <label for="ctPhone">Phone <em>*</em></label>
                        <input class="field" id="ctPhone" type="tel" required placeholder="+234 ...">
                    </div>
                    <div class="form-row">
                        <label for="ctService">Subject</label>
                        <select class="field" id="ctService">
                            <option>Request a quote</option>
                            <option>Corporate gifting program</option>
                            <option>Event merchandize</option>
                            <option>Partnership enquiry</option>
                            <option>General support</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-row full">
                        <label for="ctMsg">Tell us about your project <em>*</em></label>
                        <textarea class="field" id="ctMsg" required placeholder="Audience, quantity, timeline, branding needs..."></textarea>
                    </div>
                    <div class="form-row full">
                        <button class="btn btn-gold btn-lg btn-block" type="submit">Send Message <span class="arr">→</span></button>
                        <p style="font-size:12px;color:var(--muted);text-align:center;margin-top:10px">We respond within 1 business day. Your details stay confidential.</p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section style="padding:0 0 clamp(64px, 9vw, 110px)">
        <div class="container">
            <div class="section-head center reveal" style="margin-bottom:34px">
                <span class="eyebrow center">Find Us</span>
                <h2 class="display h2">Visit our Lagos studio</h2>
                <p class="lead" style="margin-top:14px">We're in the heart of Opebi — a short drive from Sheraton, Allen Avenue and the Ikeja corridor.</p>
            </div>
            <div class="map-wrap reveal d1">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3963.4075883587!2d3.3565!3d6.5659!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x103b922b51dcb2e9%3A0x5e1e0f7ef0c1e1b5!2s6%20Oluwole%20Omole%20St%2C%20Opebi%2C%20Lagos%20101233%2C%20Nigeria!5e0!3m2!1sen!2sng!4v1720000000000!5m2!1sen!2sng"
                    width="100%"
                    height="100%"
                    style="border:0"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Marigold Signature location — 6 Oluwole Omole St, Opebi, Lagos">
                </iframe>
                <div class="map-card">
                    <span class="ic-ico"><i data-lucide="map-pin"></i></span>
                    <div>
                        <strong>Marigold Signature Nigeria Limited</strong>
                        <p>6 Oluwole Omole Street, Opebi, Lagos 101233, Nigeria</p>
                    </div>
                    <a class="btn btn-gold" href="https://www.google.com/maps/dir/?api=1&destination=6+Oluwole+Omole+St,+Opebi,+Lagos+101233,+Nigeria" target="_blank" rel="noopener">Get Directions</a>
                </div>
            </div>
        </div>
    </section>

</div>
