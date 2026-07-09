<div class="flex items-center gap-4 mb-6">
    <a href="/admin/quotes" class="text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
    <div class="flex-grow">
        <h1 class="text-2xl font-bold font-manrope">Quote <?= $quote['id'] ?></h1>
        <p class="text-sm text-[var(--text-secondary)]">Submitted <?= date('F j, Y', strtotime($quote['date'])) ?> · Valid until <?= date('M j, Y', strtotime($quote['valid_until'])) ?></p>
    </div>
    <div class="flex items-center gap-2">
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="download" class="w-4 h-4"></i> PDF</button>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="send" class="w-4 h-4"></i> Email to Customer</button>
        <button class="btn h-9 px-4 text-sm text-red-400 border border-red-500/40 bg-red-500/10 hover:bg-red-500/20 transition-colors">Reject</button>
        <button class="btn btn-primary h-9 px-4 text-sm">Approve Quote</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Left: Items + Files + Notes -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Items -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)] flex items-center justify-between">
                <h2 class="font-bold font-manrope">Requested Items</h2>
                <p class="text-xs text-[var(--text-muted)]">Click any price cell to edit inline</p>
            </div>
            <table class="w-full text-left">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase text-[var(--text-muted)]">
                    <th class="px-5 py-3">Product</th><th class="px-5 py-3 text-center">Qty</th>
                    <th class="px-5 py-3 text-right">Unit Price (₦)</th><th class="px-5 py-3 text-right">Total</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php foreach ($quote['items'] as $item): ?>
                    <tr x-data="{ editing: false, price: '' }">
                        <td class="px-5 py-4">
                            <p class="font-medium text-sm"><?= htmlspecialchars($item['name']) ?></p>
                            <p class="text-xs text-[var(--text-muted)] mt-0.5 italic">"<?= htmlspecialchars($item['notes']) ?>"</p>
                        </td>
                        <td class="px-5 py-4 text-center text-sm font-mono"><?= $item['qty'] ?></td>
                        <td class="px-5 py-4 text-right" @click="editing = true">
                            <div x-show="!editing" class="cursor-pointer text-[var(--gold)] font-bold text-sm hover:text-white transition-colors">
                                <span x-text="price ? '₦' + Number(price).toLocaleString() : '— Click to set —'"></span>
                            </div>
                            <div x-show="editing" class="flex items-center justify-end gap-2">
                                <input x-ref="priceInput" type="number" x-model="price" @blur="editing = false" @keydown.enter="editing = false" placeholder="0" class="input-field w-28 text-sm text-right h-8" x-init="$nextTick(() => { if(editing) $refs.priceInput.focus() })">
                            </div>
                        </td>
                        <td class="px-5 py-4 text-right text-sm text-[var(--text-muted)]" x-text="price ? '₦' + (price * <?= $item['qty'] ?>).toLocaleString() : '—'">—</td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="p-5 bg-[var(--surface)] border-t border-[var(--border)]">
                <div class="flex justify-end">
                    <button class="btn btn-primary h-9 px-6 text-sm">Convert to Order</button>
                </div>
            </div>
        </div>

        <!-- Uploaded Files -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-5">Customer's Files</h2>
            <div class="space-y-3">
                <?php foreach ($quote['files'] as $file): ?>
                <div class="flex items-center gap-4 bg-[var(--surface)] border border-[var(--border)] rounded-[10px] px-4 py-3">
                    <i data-lucide="<?= $file['icon'] ?>" class="w-5 h-5 text-[var(--gold)] shrink-0"></i>
                    <span class="text-sm flex-grow"><?= htmlspecialchars($file['name']) ?></span>
                    <span class="text-xs text-[var(--text-muted)]"><?= $file['size'] ?></span>
                    <button class="text-[var(--text-muted)] hover:text-[var(--gold)] transition-colors"><i data-lucide="download" class="w-4 h-4"></i></button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Customer Notes + Messaging -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h2 class="font-bold font-manrope">Messaging Thread</h2></div>
            <div class="p-6 bg-[var(--surface)]/30 border-b border-[var(--border)]">
                <p class="text-xs text-[var(--text-muted)] uppercase tracking-wider mb-2 font-semibold">Customer Requirements</p>
                <p class="text-sm text-[var(--text-secondary)] italic">"<?= htmlspecialchars($quote['customer_notes']) ?>"</p>
            </div>
            <div class="p-6 space-y-5">
                <?php foreach ($quote['messages'] as $msg): ?>
                <div class="flex flex-col <?= $msg['is_customer'] ? 'items-end' : 'items-start' ?>">
                    <span class="text-xs text-[var(--text-muted)] mb-1"><?= htmlspecialchars($msg['sender']) ?> · <?= $msg['time'] ?></span>
                    <div class="px-4 py-3 rounded-[12px] max-w-[80%] text-sm leading-relaxed <?= $msg['is_customer'] ? 'bg-[var(--surface)] border border-[var(--border)] rounded-tr-none' : 'bg-[var(--gold)] text-[#111] rounded-tl-none font-medium' ?>">
                        <?= htmlspecialchars($msg['message']) ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="p-4 border-t border-[var(--border)] bg-[var(--surface)] flex items-center gap-3">
                <input type="text" placeholder="Reply to customer…" class="input-field flex-grow bg-[#111] border-[var(--border)] h-10 text-sm">
                <button class="w-10 h-10 rounded-full bg-[var(--gold)] text-[#111] flex items-center justify-center hover:bg-[#d4af37]/80 transition-colors shrink-0"><i data-lucide="send" class="w-4 h-4 ml-0.5"></i></button>
            </div>
        </div>
    </div>

    <!-- Right: Customer Info + Sales Notes -->
    <div class="xl:col-span-1 space-y-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Customer</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-[var(--gold)]/20 border border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] font-bold"><?= strtoupper(substr($quote['customer']['name'], 0, 1)) ?></div>
                <div><p class="font-medium text-sm"><?= htmlspecialchars($quote['customer']['name']) ?></p><p class="text-xs text-[var(--text-muted)]"><?= $quote['customer']['email'] ?></p></div>
            </div>
            <div class="text-sm text-[var(--text-secondary)] space-y-1">
                <p><?= htmlspecialchars($quote['customer']['company']) ?></p>
                <p><?= htmlspecialchars($quote['customer']['phone']) ?></p>
            </div>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Sales Notes <span class="text-xs font-normal text-[var(--text-muted)]">(internal)</span></h3>
            <textarea rows="5" class="input-field w-full resize-none text-sm" placeholder="Add internal sales notes, pricing context, or special instructions…"></textarea>
            <button class="mt-3 btn btn-secondary border border-[var(--border)] w-full h-9 text-sm bg-[var(--surface)]">Save Notes</button>
        </div>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <button class="btn w-full text-left border border-[var(--border)] bg-[var(--surface)] h-10 text-sm px-4 flex items-center gap-3 text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="file-text" class="w-4 h-4"></i> Generate PDF Quote</button>
                <button class="btn w-full text-left border border-[var(--border)] bg-[var(--surface)] h-10 text-sm px-4 flex items-center gap-3 text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="mail" class="w-4 h-4"></i> Email Quote to Customer</button>
                <button class="btn w-full text-left border border-[var(--border)] bg-[var(--surface)] h-10 text-sm px-4 flex items-center gap-3 text-[var(--text-secondary)] hover:text-white hover:border-[var(--gold)]/50 transition-colors"><i data-lucide="clock" class="w-4 h-4"></i> Mark as Expired</button>
                <button class="btn w-full border border-[var(--gold)]/30 bg-[var(--gold)]/5 text-[var(--gold)] h-10 text-sm px-4 flex items-center justify-center gap-2 hover:bg-[var(--gold)]/10 transition-colors font-semibold"><i data-lucide="check-circle" class="w-4 h-4"></i> Approve & Convert to Order</button>
            </div>
        </div>
    </div>
</div>
