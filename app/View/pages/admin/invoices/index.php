<?php
$statuses = ['draft'=>'bg-gray-500/10 text-gray-400 border-gray-500/20','sent'=>'bg-blue-500/10 text-blue-400 border-blue-500/20','viewed'=>'bg-yellow-500/10 text-yellow-400 border-yellow-500/20','paid'=>'bg-green-500/10 text-green-400 border-green-500/20','cancelled'=>'bg-red-500/10 text-red-400 border-red-500/20','expired'=>'bg-orange-500/10 text-orange-400 border-orange-500/20'];
$filterLinks = [
    '' => 'All', 'draft'=>'Draft', 'sent'=>'Sent', 'viewed'=>'Viewed', 'paid'=>'Paid', 'cancelled'=>'Cancelled'
];
?>
<div class="p-6 lg:p-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold font-[var(--font-display)]">Invoices</h1>
            <p class="text-sm text-[var(--text-secondary)] mt-1">Create and manage customer invoices</p>
        </div>
        <a href="/admin/invoices/create" class="btn btn-primary inline-flex items-center gap-2 h-10 px-5">
            <i data-lucide="plus" class="w-4 h-4"></i> New Invoice
        </a>
    </div>

    <!-- Filters -->
    <div class="flex flex-wrap gap-2 mb-6">
        <?php foreach ($filterLinks as $val => $label): ?>
            <a href="/admin/invoices<?= $val ? '?status='.$val : '' ?>"
               class="px-3 py-1.5 rounded-full text-xs font-medium border transition-colors <?= $current === $val ? 'bg-[var(--gold)]/10 text-[var(--gold)] border-[var(--gold)]/30' : 'bg-[var(--surface)] text-[var(--text-secondary)] border-[var(--border)] hover:text-white' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </div>

    <?php if (empty($invoices)): ?>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-12 text-center">
            <i data-lucide="file-text" class="w-12 h-12 text-[var(--text-muted)] mx-auto mb-4"></i>
            <p class="text-[var(--text-secondary)]">No invoices found.</p>
            <a href="/admin/invoices/create" class="btn btn-primary mt-4 inline-flex items-center gap-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Create Invoice
            </a>
        </div>
    <?php else: ?>
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-[var(--surface)] text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                            <th class="px-5 py-3 font-medium">Invoice</th>
                            <th class="px-5 py-3 font-medium">Customer</th>
                            <th class="px-5 py-3 font-medium">Status</th>
                            <th class="px-5 py-3 font-medium text-right">Total</th>
                            <th class="px-5 py-3 font-medium">Date</th>
                            <th class="px-5 py-3 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        <?php foreach ($invoices as $inv): ?>
                            <tr class="group hover:bg-[var(--surface)]/50 transition-colors">
                                <td class="px-5 py-3">
                                    <a href="/admin/invoices/<?= $inv['id'] ?>" class="text-sm font-medium text-white hover:text-[var(--gold)] transition-colors">
                                        <?= htmlspecialchars($inv['invoice_number']) ?>
                                    </a>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="text-sm text-white"><?= htmlspecialchars($inv['customer_name']) ?></p>
                                    <p class="text-xs text-[var(--text-muted)]"><?= htmlspecialchars($inv['customer_email']) ?></p>
                                </td>
                                <td class="px-5 py-3">
                                    <?php $cls = $statuses[$inv['status']] ?? 'bg-gray-500/10 text-gray-400 border-gray-500/20'; ?>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border <?= $cls ?>">
                                        <?= ucfirst($inv['status']) ?>
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-right text-sm font-medium">
                                    <?= money_format((float)$inv['total'], $inv['currency'] ?: 'NGN') ?>
                                </td>
                                <td class="px-5 py-3 text-xs text-[var(--text-muted)]">
                                    <?= date('M j, Y', strtotime($inv['created_at'])) ?>
                                </td>
                                <td class="px-5 py-3">
                                    <a href="/admin/invoices/<?= $inv['id'] ?>" class="opacity-0 group-hover:opacity-100 transition-opacity text-[var(--text-secondary)] hover:text-white">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>lucide.createIcons();</script>
