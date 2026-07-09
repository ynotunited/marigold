<?php $isEdit = !empty($post); ?>
<form method="POST" action="<?= $isEdit ? '/admin/blog/' . $post['id'] : '/admin/blog' ?>">
<?= \App\Core\CSRF::field() ?>
<div class="flex items-center gap-4 mb-6">
    <a href="/admin/blog" class="text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
    <div class="flex-grow"><h1 class="text-2xl font-bold font-manrope"><?= $isEdit ? 'Edit Post' : 'Write Post' ?></h1></div>
    <div class="flex items-center gap-3">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Preview</button>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Save Draft</button>
        <button class="btn btn-primary h-9 px-6 text-sm">Publish Post</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Left: Content Editor -->
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <input type="text" value="<?= $isEdit ? htmlspecialchars($post['title']) : '' ?>" placeholder="Post Title" class="w-full bg-transparent text-3xl font-bold font-manrope placeholder-[var(--text-muted)] focus:outline-none mb-2">
            <div class="flex items-center gap-2 text-sm text-[var(--text-muted)] mb-6 font-mono bg-[var(--surface)] p-2 rounded border border-[var(--border)]">
                <span>marigoldsignature.com/blog/</span><input type="text" value="<?= $isEdit ? 'top-10-corporate-gift-ideas-for-2026' : '' ?>" placeholder="post-slug" class="bg-transparent border-none focus:outline-none flex-grow text-white">
            </div>
            
            <!-- Minimal Rich Text Editor Mock -->
            <div class="border border-[var(--border)] rounded-[10px] overflow-hidden">
                <div class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center gap-1 p-2 flex-wrap">
                    <?php foreach (['bold', 'italic', 'underline', 'list', 'image', 'link', 'code'] as $btn): ?>
                    <button type="button" class="w-8 h-7 rounded-[4px] hover:bg-[var(--card)] text-[var(--text-secondary)] hover:text-white transition-colors flex items-center justify-center"><i data-lucide="<?= $btn ?>" class="w-3.5 h-3.5"></i></button>
                    <?php endforeach; ?>
                </div>
                <textarea rows="15" placeholder="Start writing your post here…" class="w-full bg-transparent p-5 text-sm leading-relaxed focus:outline-none resize-none"></textarea>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Excerpt</h2>
            <textarea rows="3" placeholder="Brief summary for blog listing cards…" class="input-field w-full resize-none text-sm"><?= $isEdit ? htmlspecialchars($post['excerpt']) : '' ?></textarea>
        </div>
    </div>

    <!-- Right: Sidebar Settings -->
    <div class="xl:col-span-1 space-y-6">
        
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Publishing</h2>
            <div class="space-y-4 text-sm">
                <label class="flex items-center justify-between cursor-pointer">
                    <span class="font-medium text-[var(--text-secondary)]">Featured Post</span>
                    <div class="relative"><input type="checkbox" <?= $isEdit && $post['featured'] ? 'checked' : '' ?> class="sr-only peer"><div class="w-9 h-5 bg-[var(--surface)] rounded-full peer-checked:bg-[var(--gold)] transition-colors border border-[var(--border)]"></div><div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full peer-checked:translate-x-4 transition-transform shadow"></div></div>
                </label>
                <div>
                    <label class="block text-[var(--text-secondary)] mb-1">Author</label>
                    <select class="input-field w-full text-sm"><option><?= $isEdit ? $post['author'] : 'Super Admin' ?></option></select>
                </div>
                <div>
                    <label class="block text-[var(--text-secondary)] mb-1">Publish Date</label>
                    <input type="datetime-local" class="input-field w-full text-sm">
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Featured Image</h2>
            <div class="border-2 border-dashed border-[var(--border)] rounded-[10px] p-6 text-center hover:border-[var(--gold)]/50 transition-colors cursor-pointer group">
                <i data-lucide="image" class="w-8 h-8 text-[var(--text-muted)] group-hover:text-[var(--gold)] transition-colors mx-auto mb-2"></i>
                <p class="text-sm font-medium">Upload Image</p>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Taxonomies</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Category</label>
                    <select class="input-field w-full text-sm"><option><?= $isEdit ? $post['category'] : 'Select Category' ?></option><option>Gift Guides</option><option>Marketing</option></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Tags</label>
                    <input type="text" placeholder="Add tags separated by comma" class="input-field w-full text-sm">
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">SEO</h2>
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Meta Title</label><input type="text" class="input-field w-full text-sm"></div>
                <div><label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Meta Description</label><textarea rows="2" class="input-field w-full text-sm resize-none"></textarea></div>
            </div>
        </div>
    </div>
</div>
</form>
