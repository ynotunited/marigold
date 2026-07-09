<h2>Password Reset Request</h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>We received a request to reset the password for your Marigold Signature account. If you did not make this request, you can safely ignore this email.</p>
<p>To reset your password, please click the secure link below. This link will expire in 1 hour.</p>
<center><a href="<?= htmlspecialchars($reset_link) ?>" class="btn">Reset My Password</a></center>
