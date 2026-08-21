<div style="background: var(--ivory); color: var(--ink);">

    <!-- Hero -->
    <section class="page-hero">
        <div class="container">
            <div class="crumbs reveal"><a href="<?= app_url('/') ?>">Home</a><span>/</span><span>Request a Quote</span></div>
            <span class="eyebrow center reveal">Bespoke Pricing</span>
            <h1 class="display h1 reveal d1">Bespoke pricing <span class="gold-text">for your corporate order</span></h1>
            <p class="lead reveal d2">Tell us what you need. Our team will review your request and send back a tailored quote within 24 hours.</p>
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container" x-data="quoteBasket()">

            <form action="<?= app_url('/quote-request') ?>" method="POST" enctype="multipart/form-data" class="quote-layout">
                <?= $csrf_token ?? \App\Core\CSRF::field() ?>

                <!-- Honeypot (anti-bot) — hidden from humans -->
                <div class="hidden" aria-hidden="true">
                    <label for="website">Leave this field empty</label>
                    <input type="text" id="website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <!-- Left: Main Form -->
                <div class="quote-main">

                    <!-- 1. Product Basket -->
                    <div>
                        <div class="q-sec-head">
                            <span class="q-no">01</span>
                            <div>
                                <h2 class="q-title">Products requested</h2>
                                <p class="q-sub">Add one or more products you'd like a quote for.</p>
                            </div>
                        </div>

                        <div class="q-items" id="quote-items">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="q-item">
                                    <div class="q-thumb">
                                        <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" x-show="item.image">
                                        <div x-show="!item.image" class="q-thumb-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:28px;height:28px;"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                        </div>
                                    </div>
                                    <div class="q-fields">
                                        <div class="form-row">
                                            <label>Product / Description <em>*</em></label>
                                            <input type="text" :name="'items[' + index + '][name]'" x-model="item.name" placeholder="e.g. Executive Leather Notebook" class="field" required>
                                        </div>
                                        <div class="form-grid">
                                            <div class="form-row">
                                                <label>Quantity <em>*</em></label>
                                                <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" min="1" placeholder="e.g. 100" class="field" required>
                                            </div>
                                            <div class="form-row">
                                                <label>Customization Notes</label>
                                                <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes" placeholder="e.g. Gold foil logo on cover" class="field">
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" @click="removeItem(index)" class="q-remove" aria-label="Remove product">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <button type="button" @click="addItem()" class="q-add" style="margin-top: 16px;">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add another product
                        </button>
                    </div>

                    <!-- 2. Artwork / Logo Upload -->
                    <div>
                        <div class="q-sec-head">
                            <span class="q-no">02</span>
                            <div>
                                <h2 class="q-title">Artwork &amp; logo</h2>
                                <p class="q-sub">Upload your logo or branding files. We'll handle the rest.</p>
                            </div>
                        </div>

                        <div class="dropzone" :class="{ over: dragOver }"
                             @dragover.prevent="dragOver = true"
                             @dragleave="dragOver = false"
                             @drop.prevent="dragOver = false; handleFileDrop($event)">
                            <div class="dz-ico">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:24px;height:24px;"><path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/><path d="M12 12v9"/><path d="m16 16-4-4-4 4"/></svg>
                            </div>
                            <p>Drag &amp; drop files here, or <span class="dz-browse" @click="$refs.fileInput.click()">browse</span></p>
                            <small>Supports JPG, PNG, PDF, DOCX, AI, EPS &mdash; max 20MB per file</small>
                            <input x-ref="fileInput" type="file" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.docx,.ai,.eps,.svg" class="hidden" @change="handleFileSelect($event)">

                            <div class="upload-list" x-show="uploadedFiles.length > 0">
                                <template x-for="(file, i) in uploadedFiles" :key="i">
                                    <div class="upload-chip">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg>
                                        <span x-text="file.name"></span>
                                        <small x-text="(file.size / 1024).toFixed(0) + ' KB'"></small>
                                        <button type="button" @click="removeFile(i)" aria-label="Remove file">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Additional Requirements -->
                    <div>
                        <div class="q-sec-head">
                            <span class="q-no">03</span>
                            <div>
                                <h2 class="q-title">Additional notes</h2>
                                <p class="q-sub">Deadline, delivery location, packaging requirements, or anything else we should know.</p>
                            </div>
                        </div>
                        <textarea name="notes" rows="5" placeholder="e.g. We need delivery to 3 different locations by December 1st. Products should be in individual gift boxes..." class="field"></textarea>
                    </div>

                </div>

                <!-- Right: Contact Info + Summary -->
                <aside class="quote-side">

                    <div class="q-card">
                        <h3 class="q-card-title">Your details</h3>
                        <div class="form-grid">
                            <div class="form-row">
                                <label>First name <em>*</em></label>
                                <input type="text" name="first_name" value="" placeholder="David" class="field" required>
                            </div>
                            <div class="form-row">
                                <label>Last name <em>*</em></label>
                                <input type="text" name="last_name" placeholder="Okon" class="field" required>
                            </div>
                        </div>
                        <div class="form-row" style="margin-top: 16px;">
                            <label>Company</label>
                            <input type="text" name="company" placeholder="TechSolutions Inc" class="field">
                        </div>
                        <div class="form-row" style="margin-top: 16px;">
                            <label>Email address <em>*</em></label>
                            <input type="email" name="email" placeholder="david@company.com" class="field" required>
                        </div>
                        <div class="form-row" style="margin-top: 16px;">
                            <label>Phone number</label>
                            <input type="tel" name="phone" placeholder="+234 801 000 0000" class="field">
                        </div>
                    </div>

                    <div class="q-card">
                        <h3 class="q-card-title">Quote summary</h3>
                        <div class="order-sum">
                            <div class="row"><span>Products</span><span x-text="items.length + ' item(s)'"></span></div>
                            <div class="row"><span>Files</span><span x-text="uploadedFiles.length + ' file(s)'"></span></div>
                            <div class="row"><span>Response time</span><span>Within 24 hrs</span></div>
                        </div>
                        <button type="submit" class="btn btn-gold btn-block btn-lg" style="margin-top: 18px;">
                            Submit quote request
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                        <p class="q-legal">By submitting you agree to our <a href="<?= app_url('/terms-and-conditions') ?>">Terms &amp; Conditions</a>.</p>
                    </div>

                    <div class="q-trust">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1 1 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                            No commitment required &mdash; quotes are free
                        </p>
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Your information is secure &amp; confidential
                        </p>
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            Dedicated account manager assigned on submission
                        </p>
                    </div>

                </aside>
            </form>
        </div>
    </section>

</div>

<script>
function quoteBasket() {
    return {
        items: [
            <?php if (!empty($preSelected)): ?>
            { name: <?= json_encode($preSelected['name'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?>, quantity: '', notes: '', image: <?= json_encode($preSelected['image'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?> }
            <?php else: ?>
            { name: '', quantity: '', notes: '', image: '' }
            <?php endif; ?>
        ],
        uploadedFiles: [],
        dragOver: false,

        addItem() {
            this.items.push({ name: '', quantity: '', notes: '', image: '' });
        },

        removeItem(index) {
            if (this.items.length > 1) {
                this.items.splice(index, 1);
            }
        },

        handleFileSelect(event) {
            const files = Array.from(event.target.files);
            files.forEach(f => {
                if (f.size <= 20 * 1024 * 1024) {
                    this.uploadedFiles.push(f);
                }
            });
        },

        handleFileDrop(event) {
            const files = Array.from(event.dataTransfer.files);
            files.forEach(f => {
                if (f.size <= 20 * 1024 * 1024) {
                    this.uploadedFiles.push(f);
                }
            });
        },

        removeFile(index) {
            this.uploadedFiles.splice(index, 1);
        }
    }
}
</script>
