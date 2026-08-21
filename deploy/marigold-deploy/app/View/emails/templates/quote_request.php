<h2>Quote Request Received</h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>Thank you for submitting a quote request to Marigold Signature.</p>
<p>Our corporate sales team has received your request (Reference: <?= htmlspecialchars($quote_id) ?>) and is currently reviewing it. We aim to respond with a detailed proposal within 24-48 hours.</p>
<p>You can track the status of your request in your portal at any time.</p>
<center><a href="<?= htmlspecialchars($action_link) ?>" class="btn">View Request Status</a></center>
