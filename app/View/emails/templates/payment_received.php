<h2>Payment Received</h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>We have successfully received your payment of <strong><?= htmlspecialchars($amount) ?></strong> for Order <?= htmlspecialchars($order_id) ?>.</p>
<p>Your order is now being processed. You will receive another notification once your items have been shipped.</p>
<center><a href="<?= htmlspecialchars($action_link) ?>" class="btn">View Order Details</a></center>
