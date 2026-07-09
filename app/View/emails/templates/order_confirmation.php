<h2>Order Confirmed</h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>Thank you for your order. We have successfully received your payment and are now processing your items.</p>

<div class="summary-box">
    <strong>Order Number:</strong> <?= htmlspecialchars($order_id) ?><br>
    <strong>Date:</strong> <?= htmlspecialchars($date) ?><br>
    <strong>Total Amount:</strong> <?= htmlspecialchars($amount) ?>
</div>

<p>You can view your full invoice and track the status of your order directly from your dashboard.</p>
<center><a href="<?= htmlspecialchars($action_link) ?>" class="btn">View Order Details</a></center>
