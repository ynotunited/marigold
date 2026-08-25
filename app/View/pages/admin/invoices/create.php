<?php
$csrf = \App\Core\CSRF::field();
$baseUrl = rtrim($_ENV['APP_URL'] ?? '', '/');
?>
<div class="p-6 lg:p-8">
    <div class="flex items-center gap-3 mb-8">
        <a href="/admin/invoices" class="text-[var(--text-secondary)] hover:text-white transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h1 class="text-2xl font-bold font-[var(--font-display)]">Create Invoice</h1>
    </div>

    <form id="invoiceForm" action="/admin/invoices" method="POST">
        <?= \App\Core\CSRF::field() ?>

        <!-- Customer Info -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 mb-6">
            <h2 class="text-lg font-bold mb-4">Customer Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Name *</label>
                    <input type="text" name="customer_name" required class="input-field w-full h-10 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Email *</label>
                    <input type="email" name="customer_email" required class="input-field w-full h-10 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Phone</label>
                    <input type="text" name="customer_phone" class="input-field w-full h-10 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                </div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 mb-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold">Line Items</h2>
                <button type="button" onclick="addCustomItem()" class="btn btn-secondary text-xs h-8 px-3 inline-flex items-center gap-1">
                    <i data-lucide="plus" class="w-3 h-3"></i> Add Custom Item
                </button>
            </div>

            <!-- Product Picker -->
            <div class="mb-4 p-3 bg-[var(--surface)] rounded-[8px] border border-[var(--border)]">
                <p class="text-xs text-[var(--text-muted)] mb-2">Quick add from catalogue:</p>
                <select id="productPicker" onchange="addProductFromCatalogue()" class="h-9 px-3 text-sm bg-[var(--bg-primary)] border border-[var(--border)] rounded-[8px] text-white w-full">
                    <option value="">Select a product...</option>
                    <?php foreach ($products as $p): ?>
                        <option value="<?= $p['id'] ?>" data-name="<?= htmlspecialchars($p['name']) ?>" data-price="<?= (float)($p['sale_price'] ?: $p['price']) ?>">
                            <?= htmlspecialchars($p['name']) ?> — <?= money_format((float)($p['sale_price'] ?: $p['price']), 'NGN') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Items Table -->
            <div id="itemsContainer">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="text-xs uppercase tracking-wider text-[var(--text-muted)] border-b border-[var(--border)]">
                            <th class="pb-2 font-medium">Item</th>
                            <th class="pb-2 font-medium w-20">Qty</th>
                            <th class="pb-2 font-medium w-32">Unit Price</th>
                            <th class="pb-2 font-medium w-32 text-right">Total</th>
                            <th class="pb-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                    </tbody>
                </table>
                <p id="noItems" class="text-sm text-[var(--text-muted)] text-center py-6">No items added yet.</p>
            </div>
        </div>

        <!-- Totals -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" value="7.5" step="0.01" min="0" class="input-field w-full h-10 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" onchange="recalcTotals()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Discount (<?= $GLOBALS['CURRENCY_SYMBOL'] ?? '₦' ?>)</label>
                    <input type="number" name="discount" value="0" step="0.01" min="0" class="input-field w-full h-10 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white" onchange="recalcTotals()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Due Date</label>
                    <input type="date" name="due_date" class="input-field w-full h-10 px-3 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white">
                </div>
            </div>
            <div class="mt-4 text-right space-y-1">
                <p class="text-sm text-[var(--text-secondary)]">Subtotal: <span id="subtotalDisplay">₦0</span></p>
                <p class="text-sm text-[var(--text-secondary)]">Tax: <span id="taxDisplay">₦0</span></p>
                <p class="text-sm text-[var(--text-secondary)]">Discount: <span id="discountDisplay">-₦0</span></p>
                <p class="text-lg font-bold text-[var(--gold)]">Total: <span id="totalDisplay">₦0</span></p>
            </div>
        </div>

        <!-- Notes -->
        <div class="bg-[#111] border border-[var(--border)] rounded-[16px] p-6 mb-6">
            <label class="block text-sm font-medium text-[var(--text-secondary)] mb-2">Notes (optional)</label>
            <textarea name="notes" rows="3" class="input-field w-full px-3 py-2 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[8px] text-white resize-none" placeholder="Payment terms, special instructions..."></textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="/admin/invoices" class="btn btn-secondary h-10 px-6">Cancel</a>
            <button type="submit" class="btn btn-primary h-10 px-6 inline-flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Create Invoice
            </button>
        </div>
    </form>
</div>

<script>
lucide.createIcons();
var itemIndex = 0;
var products = <?= json_encode(array_map(fn($p) => ['id'=>$p['id'],'name'=>$p['name'],'price'=>(float)($p['sale_price']?:$p['price'])], $products)) ?>;

function addCustomItem() {
    addItemRow({ name: '', price: 0, qty: 1, is_custom: 1, product_id: null });
}

function addProductFromCatalogue() {
    var sel = document.getElementById('productPicker');
    var opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    addItemRow({ name: opt.dataset.name, price: parseFloat(opt.dataset.price), qty: 1, is_custom: 0, product_id: parseInt(opt.value) });
    sel.selectedIndex = 0;
}

function addItemRow(item) {
    var tbody = document.getElementById('itemsBody');
    var tr = document.createElement('tr');
    tr.className = 'border-b border-[var(--border)]';
    tr.innerHTML =
        '<td class="py-2 pr-2">' +
            '<input type="hidden" name="item_product_id[]" value="' + (item.product_id || '') + '">' +
            '<input type="hidden" name="item_custom[]" value="' + (item.is_custom || 0) + '">' +
            '<input type="text" name="item_name[]" value="' + escHtml(item.name) + '" required placeholder="Item name" class="w-full h-8 px-2 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[6px] text-white">' +
            '<input type="text" name="item_desc[]" placeholder="Description (optional)" class="w-full h-7 px-2 text-xs bg-transparent border-0 text-[var(--text-muted)] mt-1">' +
        '</td>' +
        '<td class="py-2"><input type="number" name="item_qty[]" value="' + item.qty + '" min="1" class="w-full h-8 px-2 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[6px] text-white text-center" onchange="recalcTotals()"></td>' +
        '<td class="py-2"><input type="number" name="item_price[]" value="' + item.price + '" min="0" step="0.01" class="w-full h-8 px-2 text-sm bg-[var(--surface)] border border-[var(--border)] rounded-[6px] text-white" onchange="recalcTotals()"></td>' +
        '<td class="py-2 text-right text-sm font-medium item-total">₦0</td>' +
        '<td class="py-2 text-center"><button type="button" onclick="removeItem(this)" class="text-[var(--text-muted)] hover:text-red-400"><i data-lucide="x" class="w-4 h-4"></i></button></td>';
    tbody.appendChild(tr);
    lucide.createIcons();
    recalcTotals();
    document.getElementById('noItems').style.display = 'none';
}

function removeItem(btn) {
    btn.closest('tr').remove();
    recalcTotals();
    if (!document.getElementById('itemsBody').children.length) {
        document.getElementById('noItems').style.display = '';
    }
}

function recalcTotals() {
    var rows = document.querySelectorAll('#itemsBody tr');
    var subtotal = 0;
    rows.forEach(function(r) {
        var qty = parseFloat(r.querySelector('[name="item_qty[]"]').value) || 0;
        var price = parseFloat(r.querySelector('[name="item_price[]"]').value) || 0;
        var lineTotal = qty * price;
        r.querySelector('.item-total').textContent = '₦' + lineTotal.toLocaleString('en-NG', {minimumFractionDigits:0, maximumFractionDigits:0});
        subtotal += lineTotal;
    });
    var taxRate = parseFloat(document.querySelector('[name="tax_rate"]').value) || 0;
    var discount = parseFloat(document.querySelector('[name="discount"]').value) || 0;
    var tax = subtotal * taxRate / 100;
    var total = Math.max(0, subtotal + tax - discount);
    document.getElementById('subtotalDisplay').textContent = '₦' + subtotal.toLocaleString('en-NG');
    document.getElementById('taxDisplay').textContent = '₦' + tax.toLocaleString('en-NG', {maximumFractionDigits:2});
    document.getElementById('discountDisplay').textContent = '-₦' + discount.toLocaleString('en-NG', {maximumFractionDigits:2});
    document.getElementById('totalDisplay').textContent = '₦' + total.toLocaleString('en-NG', {maximumFractionDigits:2});
}

function escHtml(s) {
    var d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}
</script>
