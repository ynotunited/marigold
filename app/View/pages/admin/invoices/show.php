<?php
$csrf = \App\Core\CSRF::field();
$statuses = ['draft'=>'bg-gray-500/10 text-gray-400 border-gray-500/20','sent'=>'bg-blue-500/10 text-blue-400 border-blue-500/20','viewed'=>'bg-yellow-500/10 text-yellow-400 border-yellow-500/20','paid'=>'bg-green-500/10 text-green-400 border-green-500/20','cancelled'=>'bg-red-500/10 text-red-400 border-red-500/20','expired'=>'bg-orange-500/10 text-orange-400 border-orange-500/20'];
$inv = $invoice;
$cls = $statuses[$inv['status']] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20';
$canSend = in_array($inv['status'], ['draft','sent','viewed'], true);
$canCancel = !in_array($inv['status'], ['paid','cancelled'], true);
$whatsappUrl = 'https://wa.me/' . preg_replace('/[^0-9]/', '', $inv['customer_phone'] ?? '') . '?text=' . urlencode("Hello {$inv['customer_name']}, here is your invoice {$inv['invoice_number']}: {$invoice_url}");
$emailUrl = 'mailto:' . $inv['customer_email'] . '?subject=' . urlencode("Invoice {$inv['invoice_number']} from Marigold Signature") . '&body=' . urlencode("Hello {$inv['customer_name']},\n\nPlease review and pay your invoice here:\n{$invoice_url}\n\nThank you,\nMarigold Signature");
?>
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-3">
            <a href="/admin/invoices" class="text-[var(--text-secondary)] hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold font-[var(--font-display)]"><?= htmlspecialchars($inv['invoice_number']) ?></h1>
                <p class="text-sm text-[var(--text-secondary)]">Created <?= date('M j, Y g:i A', strtotime($inv['created_at'])) ?></p>
            </div>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $cls ?>">
                <?= ucfirst($inv['status']) ?>
            </span>
        </div>
        <div class="flex flex-wrap gap-2">
            <?php if ($canSend): ?>
                <form action="/admin/invoices/<?= $inv['id'] ?>/send" method="POST" class="inline">
                    <?= $csrf ?>
                    <button type="submit" class="btn btn-secondary h-9 px-4 text-sm inline-flex items-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Mark Sent
                    </button>
                </form>
            <?php endif; ?>
            <?php if ($canCancel): ?>
                <form action="/admin/invoices/<?= $inv['id'] ?>/cancel" method="POST" class="inline" onsubmit="return confirm('Cancel this invoice?')">
                    <?= $csrf ?>
                    <button type="submit" class="btn btn-secondary h-9 px-4 text-sm text-red-400 border-red-500/30 hover:bg-red-500/10 inline-flex items-center gap-2">
                        <i data-lucide="x-circle" class="w-4 h-4"></i> Cancel
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Share Link -->
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-3">Share Invoice</h2>
                <div class="flex items-center gap-2 mb-4">
                    <input type="text" id="invoiceLink" value="<?= htmlspecialchars($invoice_url) ?>" readonly
                           class="flex-1 h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white font-mono">
                    <button type="button" onclick="copyLink()" class="btn btn-secondary h-9 px-4 text-sm inline-flex items-center gap-1" id="copyBtn">
                        <i data-lucide="copy" class="w-4 h-4"></i> Copy
                    </button>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="<?= $whatsappUrl ?>" target="_blank" class="btn btn-secondary h-9 px-4 text-sm inline-flex items-center gap-1 bg-green-600/10 border-green-600/30 text-green-400 hover:bg-green-600/20">
                        <i data-lucide="message-circle" class="w-4 h-4"></i> WhatsApp
                    </a>
                    <a href="<?= $emailUrl ?>" class="btn btn-secondary h-9 px-4 text-sm inline-flex items-center gap-1">
                        <i data-lucide="mail" class="w-4 h-4"></i> Email
                    </a>
                </div>
            </div>

            <!-- Items Table -->
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left whitespace-nowrap">
                        <thead>
                            <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                                <th class="px-5 py-3 font-medium">Item</th>
                                <th class="px-5 py-3 font-medium text-center">Qty</th>
                                <th class="px-5 py-3 font-medium text-right">Price</th>
                                <th class="px-5 py-3 font-medium text-right">Total</th>
                                <th class="px-5 py-3 font-medium w-10"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[var(--border)]">
                            <?php foreach ($inv['items'] as $item): ?>
                                <tr class="group hover:bg-[var(--surface)]/50">
                                    <td class="px-5 py-3">
                                        <p class="text-sm font-medium text-white"><?= htmlspecialchars($item['name']) ?></p>
                                        <?php if ($item['description']): ?>
                                            <p class="text-xs text-[var(--text-muted)]"><?= htmlspecialchars($item['description']) ?></p>
                                        <?php endif; ?>
                                        <?php if ($item['is_custom']): ?>
                                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-purple-500/10 text-purple-400 border border-purple-500/20">Custom</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-5 py-3 text-center text-sm"><?= $item['quantity'] ?></td>
                                    <td class="px-5 py-3 text-right text-sm"><?= money_format((float)$item['unit_price'], $inv['currency'] ?: 'NGN') ?></td>
                                    <td class="px-5 py-3 text-right text-sm font-medium"><?= money_format((float)$item['total'], $inv['currency'] ?: 'NGN') ?></td>
                                    <td class="px-5 py-3">
                                        <form action="/admin/invoices/<?= $inv['id'] ?>/items/<?= $item['id'] ?>/remove" method="POST" class="inline" onsubmit="return confirm('Remove this item?')">
                                            <?= $csrf ?>
                                            <button type="submit" class="opacity-0 group-hover:opacity-100 transition-opacity text-[var(--text-muted)] hover:text-red-400">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Item Inline -->
            <?php if (in_array($inv['status'], ['draft','sent','viewed'], true)): ?>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h3 class="text-sm font-bold mb-3">Add Item</h3>
                <form action="/admin/invoices/<?= $inv['id'] ?>/items" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <?= $csrf ?>
                    <div>
                        <input type="text" name="name" required placeholder="Item name" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                    </div>
                    <div class="flex gap-2">
                        <input type="number" name="quantity" value="1" min="1" class="w-20 h-9 px-2 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white text-center">
                        <input type="number" name="unit_price" step="0.01" min="0" required placeholder="Price" class="flex-1 h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                    </div>
                    <div>
                        <input type="text" name="description" placeholder="Description (optional)" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                    </div>
                    <div class="flex gap-2">
                        <label class="flex items-center gap-1 text-xs text-[var(--text-muted)]">
                            <input type="checkbox" name="is_custom" value="1" class="rounded"> Custom
                        </label>
                        <button type="submit" class="btn btn-primary h-9 px-4 text-sm flex-1">Add</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- Payment History -->
            <?php if (!empty($inv['payments'])): ?>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-3">Payment History</h2>
                <div class="space-y-2">
                    <?php foreach ($inv['payments'] as $pay): ?>
                        <div class="flex items-center justify-between text-sm py-2 border-b border-[var(--border)] last:border-0">
                            <div>
                                <span class="font-medium"><?= htmlspecialchars(ucfirst($pay['gateway'])) ?></span>
                                <span class="text-[var(--text-muted)]">— <?= htmlspecialchars($pay['reference']) ?></span>
                            </div>
                            <div class="text-right">
                                <?php if ($pay['status'] === 'success'): ?>
                                    <span class="text-green-400 font-medium">Paid</span>
                                <?php elseif ($pay['status'] === 'failed'): ?>
                                    <span class="text-red-400">Failed</span>
                                <?php else: ?>
                                    <span class="text-yellow-400">Pending</span>
                                <?php endif; ?>
                                <p class="text-xs text-[var(--text-muted)]"><?= $pay['paid_at'] ? date('M j, Y g:i A', strtotime($pay['paid_at'])) : date('M j, Y g:i A', strtotime($pay['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Info -->
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-3">Customer</h2>
                <div class="space-y-2 text-sm">
                    <p class="text-white font-medium"><?= htmlspecialchars($inv['customer_name']) ?></p>
                    <p class="text-[var(--text-secondary)]"><?= htmlspecialchars($inv['customer_email']) ?></p>
                    <?php if ($inv['customer_phone']): ?>
                        <p class="text-[var(--text-secondary)]"><?= htmlspecialchars($inv['customer_phone']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Totals -->
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-3">Summary</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-[var(--text-secondary)]">Subtotal</span>
                        <span class="text-white"><?= money_format((float)$inv['subtotal'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-[var(--text-secondary)]">Tax (<?= $inv['tax_rate'] ?>%)</span>
                        <span class="text-white"><?= money_format((float)$inv['tax_amount'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                    <?php if ((float)$inv['discount_amount'] > 0): ?>
                    <div class="flex justify-between">
                        <span class="text-[var(--text-secondary)]">Discount</span>
                        <span class="text-red-400">-<?= money_format((float)$inv['discount_amount'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="flex justify-between pt-2 border-t border-[var(--border)]">
                        <span class="text-white font-bold">Total</span>
                        <span class="text-[var(--gold)] font-bold text-lg"><?= money_format((float)$inv['total'], $inv['currency'] ?: 'NGN') ?></span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if ($inv['notes']): ?>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-3">Notes</h2>
                <p class="text-sm text-[var(--text-secondary)] whitespace-pre-wrap"><?= htmlspecialchars($inv['notes']) ?></p>
            </div>
            <?php endif; ?>

            <!-- Due Date -->
            <?php if ($inv['due_date']): ?>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-1">Due Date</h2>
                <p class="text-sm <?= strtotime($inv['due_date']) < time() && $inv['status'] !== 'paid' ? 'text-red-400' : 'text-[var(--text-secondary)]' ?>">
                    <?= date('M j, Y', strtotime($inv['due_date'])) ?>
                    <?php if (strtotime($inv['due_date']) < time() && $inv['status'] !== 'paid'): ?>
                        <span class="text-xs">(overdue)</span>
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- Paid At -->
            <?php if ($inv['paid_at']): ?>
            <div class="bg-green-500/5 border border-green-500/20 rounded-[16px] p-6">
                <div class="flex items-center gap-2 text-green-400">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                    <span class="font-bold text-sm">Paid <?= date('M j, Y g:i A', strtotime($inv['paid_at'])) ?></span>
                </div>
                <?php if ($inv['payment_gateway']): ?>
                    <p class="text-xs text-green-400/70 mt-1">via <?= htmlspecialchars(ucfirst($inv['payment_gateway'])) ?></p>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <!-- Edit Details (draft only) -->
            <?php if ($inv['status'] === 'draft'): ?>
            <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
                <h2 class="text-sm font-bold mb-3">Edit Details</h2>
                <form action="/admin/invoices/<?= $inv['id'] ?>" method="POST">
                    <?= $csrf ?>
                    <div class="space-y-3">
                        <input type="text" name="customer_name" value="<?= htmlspecialchars($inv['customer_name']) ?>" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" placeholder="Customer name">
                        <input type="email" name="customer_email" value="<?= htmlspecialchars($inv['customer_email']) ?>" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" placeholder="Customer email">
                        <input type="text" name="customer_phone" value="<?= htmlspecialchars($inv['customer_phone'] ?? '') ?>" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" placeholder="Phone">
                        <input type="number" name="tax_rate" value="<?= $inv['tax_rate'] ?>" step="0.01" min="0" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" placeholder="Tax rate %">
                        <input type="number" name="discount" value="<?= $inv['discount_amount'] ?>" step="0.01" min="0" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" placeholder="Discount">
                        <input type="date" name="due_date" value="<?= $inv['due_date'] ?? '' ?>" class="w-full h-9 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                        <textarea name="notes" rows="2" class="w-full px-3 py-2 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white resize-none" placeholder="Notes"><?= htmlspecialchars($inv['notes'] ?? '') ?></textarea>
                        <button type="submit" class="btn btn-primary w-full h-9 text-sm">Update</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- Danger Zone -->
            <div class="bg-[#111] border border-red-500/20 rounded-[16px] p-6">
                <h2 class="text-sm font-bold text-red-400 mb-3">Danger Zone</h2>
                <form action="/admin/invoices/<?= $inv['id'] ?>/delete" method="POST" onsubmit="return confirm('Permanently delete this invoice?')">
                    <?= $csrf ?>
                    <button type="submit" class="btn w-full h-9 text-sm border border-red-500/30 text-red-400 hover:bg-red-500/10">
                        Delete Invoice
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
lucide.createIcons();
function copyLink() {
    var inp = document.getElementById('invoiceLink');
    inp.select();
    navigator.clipboard.writeText(inp.value).then(function() {
        var btn = document.getElementById('copyBtn');
        btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Copied';
        lucide.createIcons();
        setTimeout(function() { btn.innerHTML = '<i data-lucide="copy" class="w-4 h-4"></i> Copy'; lucide.createIcons(); }, 2000);
    });
}
</script>
