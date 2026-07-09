<h2>Invoice for Order <?= htmlspecialchars($order_id) ?></h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>Please find attached the official invoice for your recent order.</p>
<div class="summary-box">
    <strong>Date:</strong> <?= htmlspecialchars($date) ?><br>
    <strong>Total Amount:</strong> <?= htmlspecialchars($amount) ?>
</div>
<p>If you have any questions regarding this invoice, please do not hesitate to contact our support team.</p>
<center><a href="<?= htmlspecialchars($action_link) ?>" class="btn">View Online Invoice</a></center>
