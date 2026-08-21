<h2>Payment Received</h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>We have successfully received your payment of <strong><?= htmlspecialchars($amount) ?></strong> for Order <?= htmlspecialchars($order_id) ?>.</p>
<p>Your order is now being processed. You will receive another notification once your items have been shipped.</p>
<center><a href="<?= htmlspecialchars($action_link) ?>" class="btn">View Order Details</a></center>
<?php if (!empty($user_local_date)): ?>
<p style="font-size:12px;color:#999;margin-top:24px;">This notification was sent at <?= htmlspecialchars($user_local_time) ?> on <?= htmlspecialchars($user_local_date) ?> (<?= htmlspecialchars($user_timezone ?? 'WAT') ?>).</p>
<?php endif; ?>
