<?php $isEdit = !empty($post); ?>
<form method="POST" enctype="multipart/form-data" action="<?= $isEdit ? app_url('/admin/blog/' . $post['id']) : app_url('/admin/blog') ?>">
<?= \App\Core\CSRF::field() ?>
<div class="flex items-center gap-4 mb-6">
    <a href="<?= app_url('/admin/blog') ?>" class="text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
    <div class="flex-grow"><h1 class="text-2xl font-bold font-manrope"><?= $isEdit ? 'Edit Post' : 'Write Post' ?></h1></div>
    <div class="flex items-center gap-3">
        <?php if ($isEdit && $post['slug']): ?>
        <a href="<?= app_url('/blog/' . $post['slug']) ?>" target="_blank" class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Preview</a>
        <?php endif; ?>
        <button type="submit" name="save_draft" value="1" class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Save Draft</button>
        <button type="submit" class="btn btn-primary h-9 px-6 text-sm">Publish Post</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- Left: Content Editor -->
    <div class="xl:col-span-2 space-y-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <input type="text" name="title" value="<?= $isEdit ? htmlspecialchars($post['title']) : '' ?>" placeholder="Post Title" required class="w-full bg-transparent text-3xl font-bold font-manrope placeholder-[var(--text-muted)] focus:outline-none mb-2">
            <div class="flex items-center gap-2 text-sm text-[var(--text-muted)] mb-6 font-mono bg-[var(--surface)] p-2 rounded border border-[var(--border)]">
                <span>marigoldsignatureng.com/blog/</span><input type="text" name="slug" value="<?= $isEdit ? htmlspecialchars($post['slug']) : '' ?>" placeholder="post-slug" class="bg-transparent border-none focus:outline-none flex-grow text-white">
            </div>

            <!-- Content Editor -->
            <div class="border border-[var(--border)] rounded-[10px] overflow-hidden">
                <div class="bg-[var(--surface)] border-b border-[var(--border)] flex items-center gap-1 p-2 flex-wrap">
                    <?php foreach (['bold', 'italic', 'underline', 'list', 'image', 'link', 'code'] as $btn): ?>
                    <button type="button" class="w-8 h-7 rounded-[4px] hover:bg-[var(--card)] text-[var(--text-secondary)] hover:text-white transition-colors flex items-center justify-center"><i data-lucide="<?= $btn ?>" class="w-3.5 h-3.5"></i></button>
                    <?php endforeach; ?>
                </div>
                <textarea name="content" rows="15" placeholder="Start writing your post here…" class="w-full bg-transparent p-5 text-sm leading-relaxed focus:outline-none resize-none"><?= $isEdit ? htmlspecialchars($post['content'] ?? '') : '' ?></textarea>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Excerpt</h2>
            <textarea name="excerpt" rows="3" placeholder="Brief summary for blog listing cards…" class="input-field w-full resize-none text-sm"><?= $isEdit ? htmlspecialchars($post['excerpt']) : '' ?></textarea>
        </div>
    </div>

    <!-- Right: Sidebar Settings -->
    <div class="xl:col-span-1 space-y-6">

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Publishing</h2>
            <div class="space-y-4 text-sm">
                <div>
                    <label class="block text-[var(--text-secondary)] mb-1">Status</label>
                    <select name="status" class="input-field w-full text-sm" x-data x-init="$el.value = '<?= $isEdit ? $post['status'] : 'published' ?>'">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[var(--text-secondary)] mb-1">Author</label>
                    <select name="author_id" class="input-field w-full text-sm">
                        <?php foreach ($authors ?? [] as $a): ?>
                        <option value="<?= $a['id'] ?>" <?= $isEdit && (int)$post['author_id'] === (int)$a['id'] ? 'selected' : '' ?>><?= htmlspecialchars($a['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[var(--text-secondary)] mb-1">Publish Date</label>
                    <input type="datetime-local" name="published_at" value="<?= $isEdit && $post['published_at'] ? date('Y-m-d\TH:i', strtotime($post['published_at'])) : '' ?>" class="input-field w-full text-sm">
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Featured Image</h2>
            <div class="border-2 border-dashed border-[var(--border)] rounded-[10px] p-6 text-center hover:border-[var(--gold)]/50 transition-colors cursor-pointer group" @click="$refs.postImage.click()">
                <i data-lucide="image" class="w-8 h-8 text-[var(--text-muted)] group-hover:text-[var(--gold)] transition-colors mx-auto mb-2"></i>
                <p class="text-sm font-medium">Upload Image</p>
                <?php if ($isEdit && $post['featured_image']): ?>
                <p class="text-xs text-[var(--gold)] mt-2">Current: <?= htmlspecialchars($post['featured_image']) ?></p>
                <?php endif; ?>
                <input x-ref="postImage" type="file" name="featured_image" accept="image/*" class="sr-only">
                <input type="hidden" name="featured_image_url" value="<?= htmlspecialchars($post['featured_image'] ?? '') ?>">
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Taxonomies</h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-1.5">Category</label>
                    <select name="category_id" class="input-field w-full text-sm">
                        <option value="0">Uncategorized</option>
                        <?php foreach ($categories ?? [] as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $isEdit && $post['category'] === $cat['name'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">SEO</h2>
            <div class="space-y-4">
                <div><label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Meta Title</label><input type="text" name="meta_title" value="<?= $isEdit ? htmlspecialchars($post['meta_title'] ?? '') : '' ?>" class="input-field w-full text-sm"></div>
                <div><label class="block text-sm font-medium text-[var(--text-secondary)] mb-1">Meta Description</label><textarea rows="2" name="meta_description" class="input-field w-full text-sm resize-none"><?= $isEdit ? htmlspecialchars($post['meta_description'] ?? '') : '' ?></textarea></div>
            </div>
        </div>
    </div>
</div>
</form>
