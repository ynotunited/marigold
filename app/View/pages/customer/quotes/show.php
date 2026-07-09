<div class="mb-6">
    <a href="/account/quotes" class="inline-flex items-center text-sm text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Quotes
    </a>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <h1 class="text-3xl font-bold font-manrope">Quote <?= $quote['id'] ?></h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-400 border border-yellow-500/20">
                    <?= $quote['status'] ?>
                </span>
            </div>
            <p class="text-[var(--text-secondary)]">Requested on <?= date('F j, Y', strtotime($quote['date'])) ?> • Valid until <?= date('M j, Y', strtotime($quote['valid_until'])) ?></p>
        </div>
        
        <div class="flex items-center gap-3">
            <button class="btn btn-secondary border border-[var(--border)] h-[40px] px-4 text-sm bg-[var(--surface)]">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Download PDF
            </button>
            <?php if($quote['status'] !== 'Pending Review'): ?>
            <button class="btn btn-primary h-[40px] px-6 text-sm">
                Accept & Pay
            </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Col: Details -->
    <div class="lg:col-span-2 space-y-8">
        
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-xl font-bold font-manrope">Requested Items</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead>
                        <tr class="bg-[var(--surface)] text-[var(--text-muted)] text-sm">
                            <th class="px-6 py-4 font-medium">Item Details</th>
                            <th class="px-6 py-4 font-medium text-center">Qty</th>
                            <th class="px-6 py-4 font-medium text-right">Unit Price</th>
                            <th class="px-6 py-4 font-medium text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--border)]">
                        <?php foreach($quote['items'] as $item): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-normal min-w-[250px]">
                                <span class="font-medium text-white block mb-1"><?= htmlspecialchars($item['name']) ?></span>
                                <span class="text-xs text-[var(--text-secondary)] block italic px-2 py-1 bg-[var(--surface)] rounded border border-[var(--border)]">"<?= htmlspecialchars($item['notes']) ?>"</span>
                            </td>
                            <td class="px-6 py-4 text-center text-[var(--text-secondary)]"><?= $item['quantity'] ?></td>
                            <td class="px-6 py-4 text-right text-[var(--text-secondary)]"><?= $item['price'] ?></td>
                            <td class="px-6 py-4 text-right font-medium text-[var(--gold)]"><?= $item['total'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="p-6 bg-[var(--surface)] border-t border-[var(--border)] flex justify-end">
                <div class="flex items-center gap-6">
                    <span class="text-[var(--text-secondary)] uppercase text-sm tracking-wider font-medium">Estimated Total</span>
                    <span class="text-2xl font-bold font-manrope text-white"><?= $quote['total'] ?></span>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Right Col: Messaging -->
    <div class="lg:col-span-1">
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] flex flex-col h-[600px]">
            <div class="p-6 border-b border-[var(--border)] shrink-0">
                <h2 class="text-xl font-bold font-manrope">Discussion</h2>
                <p class="text-sm text-[var(--text-secondary)] mt-1">Chat directly with your account manager.</p>
            </div>
            
            <!-- Chat Window -->
            <div class="flex-grow p-6 overflow-y-auto flex flex-col gap-6">
                <?php foreach($quote['messages'] as $msg): ?>
                    <div class="flex flex-col <?= $msg['is_customer'] ? 'items-end' : 'items-start' ?>">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs text-[var(--text-muted)] font-medium"><?= htmlspecialchars($msg['sender']) ?></span>
                            <span class="text-[10px] text-[var(--text-muted)]/50">• <?= $msg['time'] ?></span>
                        </div>
                        <div class="px-4 py-3 rounded-[12px] max-w-[90%] text-sm leading-relaxed <?= $msg['is_customer'] ? 'bg-[var(--gold)] text-[#111] rounded-tr-none font-medium' : 'bg-[var(--surface)] border border-[var(--border)] rounded-tl-none' ?>">
                            <?= htmlspecialchars($msg['message']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Chat Input -->
            <div class="p-4 border-t border-[var(--border)] shrink-0 bg-[var(--surface)] rounded-b-[16px]">
                <form class="flex items-center gap-3" onsubmit="event.preventDefault()">
                    <input type="text" placeholder="Type a message..." class="input-field flex-grow bg-[var(--bg-primary)] border-[var(--border)] h-10 text-sm">
                    <button class="w-10 h-10 rounded-full bg-[var(--gold)] text-[#111] flex items-center justify-center shrink-0 hover:bg-[#d4af37]/80 transition-colors">
                        <i data-lucide="send" class="w-4 h-4 ml-1"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
