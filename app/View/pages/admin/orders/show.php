<div class="flex items-center gap-4 mb-6">
    <a href="/admin/orders" class="text-[var(--text-secondary)] hover:text-white transition-colors"><i data-lucide="arrow-left" class="w-5 h-5"></i></a>
    <div class="flex-grow">
        <h1 class="text-2xl font-bold font-manrope">Order <?= $order['id'] ?></h1>
        <p class="text-sm text-[var(--text-secondary)]">Placed on <?= date('F j, Y g:i A', strtotime($order['date'])) ?></p>
    </div>
    <div class="flex items-center gap-3">
        <select class="input-field h-9 text-sm bg-[var(--surface)] border-[var(--border)] min-w-[160px]">
            <option>Update Status…</option>
            <option>Pending</option><option>Processing</option><option>Shipped</option><option>Completed</option><option>Cancelled</option>
        </select>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="printer" class="w-4 h-4"></i> Invoice</button>
        <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)] flex items-center gap-2"><i data-lucide="clipboard-list" class="w-4 h-4"></i> Packing Slip</button>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Left: Items + Timeline -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Items Table -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-5 border-b border-[var(--border)]"><h2 class="font-bold font-manrope">Items</h2></div>
            <table class="w-full text-left">
                <thead><tr class="bg-[var(--surface)] text-xs uppercase text-[var(--text-muted)]">
                    <th class="px-5 py-3">Product</th><th class="px-5 py-3 text-center">Qty</th>
                    <th class="px-5 py-3 text-right">Unit</th><th class="px-5 py-3 text-right">Total</th>
                </tr></thead>
                <tbody class="divide-y divide-[var(--border)]">
                    <?php foreach ($order['items'] as $item): ?>
                    <tr>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[6px] overflow-hidden bg-[var(--surface)] shrink-0"><img src="<?= $item['image'] ?>" class="w-full h-full object-cover"></div>
                                <div><p class="font-medium text-sm"><?= htmlspecialchars($item['name']) ?></p><p class="text-xs text-[var(--text-muted)] font-mono"><?= $item['sku'] ?></p></div>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-center text-sm"><?= $item['qty'] ?></td>
                        <td class="px-5 py-4 text-right text-sm">₦<?= number_format($item['price']) ?></td>
                        <td class="px-5 py-4 text-right font-bold text-sm text-[var(--gold)]">₦<?= number_format($item['total']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="p-5 bg-[var(--surface)] border-t border-[var(--border)]">
                <div class="space-y-2 max-w-xs ml-auto text-sm">
                    <div class="flex justify-between text-[var(--text-secondary)]"><span>Subtotal</span><span>₦<?= number_format($order['subtotal']) ?></span></div>
                    <div class="flex justify-between text-[var(--text-secondary)]"><span>Shipping</span><span>₦<?= number_format($order['shipping']) ?></span></div>
                    <div class="flex justify-between font-bold text-base pt-2 border-t border-[var(--border)]"><span>Total</span><span class="text-[var(--gold)]">₦<?= number_format($order['total']) ?></span></div>
                </div>
            </div>
        </div>

        <!-- Order Timeline -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-6">Order Timeline</h2>
            <div class="relative">
                <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-[var(--border)]"></div>
                <div class="absolute left-[19px] top-2 h-2/5 w-0.5 bg-[var(--gold)]"></div>
                <ul class="space-y-6">
                    <?php foreach ($order['timeline'] as $step): ?>
                    <li class="relative pl-12 <?= !$step['done'] ? 'opacity-40' : '' ?>">
                        <div class="absolute left-0 top-1 w-10 h-10 rounded-full flex items-center justify-center z-10 border
                            <?= $step['done'] ? ($step['current'] ?? false ? 'bg-[var(--surface)] border-[var(--gold)]' : 'bg-[var(--gold)]/10 border-[var(--gold)]') : 'bg-[var(--surface)] border-[var(--border)]' ?>">
                            <?php if (!empty($step['current'])): ?><div class="w-2.5 h-2.5 rounded-full bg-[var(--gold)]"></div>
                            <?php elseif ($step['done']): ?><i data-lucide="check" class="w-4 h-4 text-[var(--gold)]"></i>
                            <?php endif; ?>
                        </div>
                        <p class="font-semibold text-sm <?= !empty($step['current']) ? 'text-[var(--gold)]' : '' ?>"><?= $step['event'] ?></p>
                        <p class="text-xs text-[var(--text-muted)] mt-0.5"><?= $step['time'] ?></p>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Internal Notes -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h2 class="font-bold font-manrope mb-4">Internal Notes <span class="text-xs font-normal text-[var(--text-muted)]">(admin-only)</span></h2>
            <textarea rows="3" placeholder="Add an internal note…" class="input-field w-full resize-none text-sm mb-3"></textarea>
            <button class="btn btn-secondary border border-[var(--border)] h-9 px-4 text-sm bg-[var(--surface)]">Add Note</button>
        </div>
    </div>

    <!-- Right: Customer + Shipping -->
    <div class="xl:col-span-1 space-y-6">
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Customer</h3>
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-[var(--gold)]/20 border border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] font-bold">
                    <?= strtoupper(substr($order['customer']['name'], 0, 1)) ?>
                </div>
                <div><p class="font-medium text-sm"><?= htmlspecialchars($order['customer']['name']) ?></p><p class="text-xs text-[var(--text-muted)]"><?= $order['customer']['email'] ?></p></div>
            </div>
            <div class="text-sm text-[var(--text-secondary)] space-y-1">
                <p><?= htmlspecialchars($order['customer']['company']) ?></p>
                <p><?= htmlspecialchars($order['customer']['phone']) ?></p>
            </div>
            <a href="/admin/customers/1" class="mt-4 text-xs text-[var(--gold)] hover:text-white transition-colors flex items-center gap-1">View customer profile <i data-lucide="arrow-right" class="w-3 h-3"></i></a>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Shipping Address</h3>
            <address class="not-italic text-sm text-[var(--text-secondary)] leading-relaxed">
                <?= $order['shipping_address']['street'] ?><br>
                <?= $order['shipping_address']['city'] ?>, <?= $order['shipping_address']['state'] ?><br>
                <?= $order['shipping_address']['country'] ?>
            </address>
        </div>

        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="font-bold font-manrope mb-4">Payment</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-[var(--text-secondary)]">Method</span><span class="font-medium"><?= $order['payment_method'] ?></span></div>
                <div class="flex justify-between"><span class="text-[var(--text-secondary)]">Status</span><span class="font-bold text-green-400"><?= $order['payment_status'] ?></span></div>
            </div>
            <div class="mt-4 pt-4 border-t border-[var(--border)] flex gap-2">
                <button class="flex-1 btn btn-secondary border border-[var(--danger)]/40 text-[var(--danger)] h-9 text-xs bg-transparent hover:bg-[var(--danger)]/10 transition-colors">Refund Order</button>
            </div>
        </div>
    </div>
</div>
