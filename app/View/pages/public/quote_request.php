<!-- Hero -->
<section class="pt-32 pb-12 px-6 lg:px-20 border-b border-[var(--border)]">
    <div class="max-w-[760px]">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-[var(--gold)]/10 border border-[var(--gold)]/30 text-[var(--gold)] text-sm font-medium mb-6">
            <i data-lucide="file-text" class="w-4 h-4"></i> Request a Custom Quote
        </div>
        <h1 class="text-4xl md:text-5xl font-bold font-manrope mb-4 leading-tight">Get bespoke pricing<br>for your corporate order</h1>
        <p class="text-lg text-[var(--text-secondary)]">Tell us what you need. Our team will review your request and send back a tailored quote within 24 hours.</p>
    </div>
</section>

<section class="py-16 px-6 lg:px-20">
    <div class="max-w-[1100px] mx-auto" x-data="quoteBasket()">
        
        <form action="/quote-request" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 xl:grid-cols-3 gap-12">
            <?php /* CSRF token would go here in production */ ?>

            <!-- Left: Main Form -->
            <div class="xl:col-span-2 space-y-10">

                <!-- 1. Product Basket -->
                <div>
                    <h2 class="text-2xl font-bold font-manrope mb-2">1. Products Requested</h2>
                    <p class="text-[var(--text-secondary)] text-sm mb-6">Add one or more products you'd like a quote for.</p>

                    <!-- Product Items -->
                    <div class="space-y-3 mb-5" id="quote-items">
                        <?php if (!empty($preSelected)): ?>
                        <template x-if="true">
                            <div></div>
                        </template>
                        <?php endif; ?>
                        
                        <template x-for="(item, index) in items" :key="index">
                            <div class="bg-[var(--card)] border border-[var(--border)] rounded-[12px] p-5 flex flex-col sm:flex-row gap-5">
                                <div class="w-20 h-20 rounded-[8px] overflow-hidden bg-[var(--surface)] shrink-0">
                                    <img :src="item.image" :alt="item.name" class="w-full h-full object-cover" x-show="item.image">
                                    <div x-show="!item.image" class="w-full h-full flex items-center justify-center text-[var(--text-muted)]">
                                        <i data-lucide="package" class="w-8 h-8"></i>
                                    </div>
                                </div>
                                <div class="flex-grow space-y-4">
                                    <div>
                                        <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Product / Description</label>
                                        <input type="text" :name="'items[' + index + '][name]'" x-model="item.name" placeholder="e.g. Executive Leather Notebook" class="input-field w-full text-sm" required>
                                    </div>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Quantity</label>
                                            <input type="number" :name="'items[' + index + '][quantity]'" x-model="item.quantity" min="1" placeholder="e.g. 100" class="input-field w-full text-sm" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1">Customization Notes</label>
                                            <input type="text" :name="'items[' + index + '][notes]'" x-model="item.notes" placeholder="e.g. Gold foil logo on cover" class="input-field w-full text-sm">
                                        </div>
                                    </div>
                                </div>
                                <button type="button" @click="removeItem(index)" class="text-[var(--text-muted)] hover:text-[var(--danger)] transition-colors self-start shrink-0 mt-1">
                                    <i data-lucide="x" class="w-5 h-5"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                    
                    <button type="button" @click="addItem()" class="flex items-center gap-2 text-sm text-[var(--gold)] font-medium hover:text-white transition-colors border border-dashed border-[var(--gold)]/40 hover:border-[var(--gold)] rounded-[10px] w-full py-3 justify-center">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Another Product
                    </button>
                </div>

                <!-- 2. Artwork / Logo Upload -->
                <div>
                    <h2 class="text-2xl font-bold font-manrope mb-2">2. Artwork & Logo</h2>
                    <p class="text-[var(--text-secondary)] text-sm mb-6">Upload your logo or branding files. We'll handle the rest.</p>

                    <div class="border-2 border-dashed border-[var(--border)] rounded-[16px] p-10 text-center hover:border-[var(--gold)]/50 transition-colors cursor-pointer group" 
                         @dragover.prevent @drop.prevent="handleFileDrop($event)">
                        <div class="w-16 h-16 rounded-full bg-[var(--surface)] flex items-center justify-center mx-auto mb-4 group-hover:bg-[var(--gold)]/10 transition-colors">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-[var(--text-secondary)] group-hover:text-[var(--gold)] transition-colors"></i>
                        </div>
                        <p class="font-medium mb-1">Drag & drop files here, or <span class="text-[var(--gold)] hover:underline cursor-pointer" @click="$refs.fileInput.click()">browse</span></p>
                        <p class="text-sm text-[var(--text-muted)]">Supports JPG, PNG, PDF, DOCX, AI, EPS — Max 20MB per file</p>
                        <input x-ref="fileInput" type="file" name="files[]" multiple accept=".jpg,.jpeg,.png,.pdf,.docx,.ai,.eps,.svg" class="sr-only" @change="handleFileSelect($event)">
                        
                        <!-- Uploaded Files Preview -->
                        <div class="mt-6 space-y-2 text-left" x-show="uploadedFiles.length > 0">
                            <template x-for="(file, i) in uploadedFiles" :key="i">
                                <div class="flex items-center gap-3 bg-[var(--surface)] border border-[var(--border)] rounded-[8px] px-4 py-2">
                                    <i data-lucide="file" class="w-4 h-4 text-[var(--gold)] shrink-0"></i>
                                    <span class="text-sm flex-grow truncate" x-text="file.name"></span>
                                    <span class="text-xs text-[var(--text-muted)]" x-text="(file.size / 1024).toFixed(0) + ' KB'"></span>
                                    <button type="button" @click="removeFile(i)" class="text-[var(--text-muted)] hover:text-[var(--danger)] transition-colors">
                                        <i data-lucide="x" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- 3. Additional Requirements -->
                <div>
                    <h2 class="text-2xl font-bold font-manrope mb-2">3. Additional Notes</h2>
                    <p class="text-[var(--text-secondary)] text-sm mb-6">Deadline, delivery location, packaging requirements, or anything else we should know.</p>
                    <textarea name="notes" rows="5" placeholder="e.g. We need delivery to 3 different locations by December 1st. Products should be in individual gift boxes..." class="input-field w-full resize-none text-sm"></textarea>
                </div>

            </div>

            <!-- Right: Contact Info + Summary -->
            <div class="xl:col-span-1 space-y-8">
                
                <!-- Sticky Sidebar -->
                <div class="sticky top-8 space-y-6">
                    
                    <!-- Contact Info -->
                    <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6">
                        <h3 class="text-lg font-bold font-manrope mb-5">Your Details</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">First Name</label>
                                    <input type="text" name="first_name" value="" placeholder="David" class="input-field w-full text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Last Name</label>
                                    <input type="text" name="last_name" placeholder="Okon" class="input-field w-full text-sm" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Company Name</label>
                                <input type="text" name="company" placeholder="TechSolutions Inc" class="input-field w-full text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Email Address</label>
                                <input type="email" name="email" placeholder="david@company.com" class="input-field w-full text-sm" required>
                            </div>
                            <div>
                                <label class="block text-xs text-[var(--text-muted)] uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="tel" name="phone" placeholder="+234 801 000 0000" class="input-field w-full text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Summary Box -->
                    <div class="bg-[var(--surface)] border border-[var(--border)] rounded-[16px] p-6">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-[var(--text-muted)] mb-4">Quote Summary</h3>
                        <div class="space-y-2 mb-5 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[var(--text-secondary)]">Products</span>
                                <span class="font-medium" x-text="items.length + ' item(s)'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--text-secondary)]">Files</span>
                                <span class="font-medium" x-text="uploadedFiles.length + ' file(s)'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--text-secondary)]">Response Time</span>
                                <span class="font-medium text-green-400">Within 24 hrs</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full h-[52px] text-base font-semibold">
                            Submit Quote Request <i data-lucide="arrow-right" class="w-5 h-5 ml-1"></i>
                        </button>
                        <p class="text-xs text-[var(--text-muted)] text-center mt-4">By submitting you agree to our <a href="/terms-and-conditions" class="text-[var(--gold)] hover:underline">Terms & Conditions</a></p>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                            <i data-lucide="shield-check" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                            No commitment required — quotes are free
                        </div>
                        <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                            <i data-lucide="lock" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                            Your information is secure & confidential
                        </div>
                        <div class="flex items-center gap-3 text-sm text-[var(--text-secondary)]">
                            <i data-lucide="clock" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                            Dedicated account manager assigned on submission
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<script>
function quoteBasket() {
    return {
        items: [
            <?php if (!empty($preSelected)): ?>
            { name: '<?= addslashes($preSelected['name']) ?>', quantity: '', notes: '', image: '<?= $preSelected['image'] ?>' }
            <?php else: ?>
            { name: '', quantity: '', notes: '', image: '' }
            <?php endif; ?>
        ],
        uploadedFiles: [],
        
        addItem() {
            this.items.push({ name: '', quantity: '', notes: '', image: '' });
            this.$nextTick(() => lucide.createIcons());
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
            this.$nextTick(() => lucide.createIcons());
        },
        
        handleFileDrop(event) {
            const files = Array.from(event.dataTransfer.files);
            files.forEach(f => {
                if (f.size <= 20 * 1024 * 1024) {
                    this.uploadedFiles.push(f);
                }
            });
            this.$nextTick(() => lucide.createIcons());
        },
        
        removeFile(index) {
            this.uploadedFiles.splice(index, 1);
        }
    }
}
</script>
