<div class="mb-6">
    <a href="/account/orders" class="inline-flex items-center text-sm text-[var(--text-secondary)] hover:text-[var(--gold)] transition-colors mb-4">
        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i> Back to Orders
    </a>
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold font-manrope">Order <?= $order['id'] ?></h1>
            <p class="text-[var(--text-secondary)] mt-1">Placed on <?= date('F j, Y', strtotime($order['date'])) ?></p>
        </div>
        
        <div class="flex items-center gap-3">
            <button class="btn btn-secondary border border-[var(--border)] h-[40px] px-4 text-sm bg-[var(--surface)]">
                <i data-lucide="download" class="w-4 h-4 mr-2"></i> Invoice
            </button>
            <button class="btn btn-primary h-[40px] px-4 text-sm">
                <i data-lucide="rotate-ccw" class="w-4 h-4 mr-2"></i> Reorder
            </button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Main Detail Col -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Tracking Stepper -->
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6 lg:p-8">
            <h2 class="text-xl font-bold font-manrope mb-8">Order Status</h2>
            
            <div class="relative">
                <!-- Vertical line -->
                <div class="absolute left-[19px] top-2 bottom-2 w-0.5 bg-[var(--border)]"></div>
                <!-- Active line portion -->
                <div class="absolute left-[19px] top-2 h-1/2 w-0.5 bg-[var(--gold)]"></div>

                <ul class="space-y-8">
                    <!-- Step 1: Ordered -->
                    <li class="relative pl-12">
                        <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-[var(--gold)]/10 flex items-center justify-center border border-[var(--gold)] z-10">
                            <div class="w-2.5 h-2.5 rounded-full bg-[var(--gold)]"></div>
                        </div>
                        <h4 class="font-bold text-white">Order Placed</h4>
                        <p class="text-sm text-[var(--text-secondary)]"><?= date('M j, Y g:i A', strtotime($order['date'])) ?></p>
                    </li>
                    
                    <!-- Step 2: Payment -->
                    <li class="relative pl-12">
                        <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-[var(--gold)]/10 flex items-center justify-center border border-[var(--gold)] z-10">
                            <div class="w-2.5 h-2.5 rounded-full bg-[var(--gold)]"></div>
                        </div>
                        <h4 class="font-bold text-white">Payment Confirmed</h4>
                        <p class="text-sm text-[var(--text-secondary)]"><?= date('M j, Y g:i A', strtotime($order['date'] . ' + 1 hour')) ?></p>
                    </li>
                    
                    <!-- Step 3: Processing -->
                    <li class="relative pl-12">
                        <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center border border-[var(--gold)] z-10">
                            <div class="w-2.5 h-2.5 rounded-full bg-[var(--gold)]"></div>
                        </div>
                        <h4 class="font-bold text-[var(--gold)]">Processing & Customization</h4>
                        <p class="text-sm text-[var(--text-secondary)]">Currently being fulfilled</p>
                    </li>
                    
                    <!-- Step 4: Shipped (Future) -->
                    <li class="relative pl-12 opacity-50">
                        <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center border border-[var(--border)] z-10">
                        </div>
                        <h4 class="font-bold">Shipped</h4>
                        <p class="text-sm text-[var(--text-secondary)]">Pending</p>
                    </li>
                    
                    <!-- Step 5: Delivered (Future) -->
                    <li class="relative pl-12 opacity-50">
                        <div class="absolute left-0 top-1 w-10 h-10 rounded-full bg-[var(--surface)] flex items-center justify-center border border-[var(--border)] z-10">
                        </div>
                        <h4 class="font-bold">Delivered</h4>
                        <p class="text-sm text-[var(--text-secondary)]">Pending</p>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Items -->
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] overflow-hidden">
            <div class="p-6 border-b border-[var(--border)]">
                <h2 class="text-xl font-bold font-manrope">Items in Order</h2>
            </div>
            
            <div class="divide-y divide-[var(--border)]">
                <?php foreach($order['items'] as $item): ?>
                <div class="p-6 flex flex-col sm:flex-row gap-6">
                    <div class="w-24 h-24 rounded-[8px] overflow-hidden shrink-0 bg-[var(--surface)]">
                        <img src="<?= $item['image'] ?>" class="w-full h-full object-cover" alt="Product">
                    </div>
                    <div class="flex-grow flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="font-bold text-lg mb-1"><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="text-[var(--text-secondary)] text-sm mb-2">Qty: <?= $item['quantity'] ?> × <?= $item['price'] ?></p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-xl text-[var(--gold)]"><?= $item['total'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Summary -->
            <div class="p-6 bg-[var(--surface)] border-t border-[var(--border)]">
                <div class="space-y-3 max-w-sm ml-auto">
                    <div class="flex justify-between text-[var(--text-secondary)]">
                        <span>Subtotal</span>
                        <span><?= $order['subtotal'] ?></span>
                    </div>
                    <div class="flex justify-between text-[var(--text-secondary)]">
                        <span>Shipping</span>
                        <span><?= $order['shipping'] ?></span>
                    </div>
                    <div class="flex justify-between text-xl font-bold font-manrope pt-3 border-t border-[var(--border)]">
                        <span>Total</span>
                        <span class="text-[var(--gold)]"><?= $order['total'] ?></span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-8">
        
        <!-- Address -->
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="text-lg font-bold font-manrope mb-4">Shipping Address</h3>
            <address class="not-italic text-[var(--text-secondary)] leading-relaxed text-sm">
                <span class="block text-white font-medium mb-1"><?= $order['shipping_address']['name'] ?></span>
                <?php if($order['shipping_address']['company']): ?>
                    <span class="block mb-1"><?= $order['shipping_address']['company'] ?></span>
                <?php endif; ?>
                <span class="block"><?= $order['shipping_address']['street'] ?></span>
                <span class="block"><?= $order['shipping_address']['city'] ?>, <?= $order['shipping_address']['state'] ?></span>
                <span class="block"><?= $order['shipping_address']['country'] ?></span>
            </address>
        </div>
        
        <!-- Support -->
        <div class="bg-[var(--card)] border border-[var(--border)] rounded-[16px] p-6">
            <h3 class="text-lg font-bold font-manrope mb-2">Need Help?</h3>
            <p class="text-[var(--text-secondary)] text-sm mb-4">Have an issue with this specific order? Contact your account manager directly.</p>
            <button class="btn w-full bg-[var(--surface)] text-white hover:bg-[var(--surface-light)] border border-[var(--border)]">
                <i data-lucide="message-square" class="w-4 h-4 mr-2"></i> Request Support
            </button>
            <?php if($order['status'] === 'Processing'): ?>
                <button class="btn w-full text-[var(--danger)] hover:bg-[var(--danger)]/10 mt-3 bg-transparent">
                    Cancel Order
                </button>
            <?php endif; ?>
        </div>
        
    </div>
</div>
