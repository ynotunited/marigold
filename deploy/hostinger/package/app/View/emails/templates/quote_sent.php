<h2>Your Quote is Ready</h2>
<p>Hello <?= htmlspecialchars($customer_name) ?>,</p>
<p>Our sales team has reviewed your request and prepared a custom quote for your corporate gifting needs.</p>

<div class="summary-box">
    <strong>Quote Reference:</strong> <?= htmlspecialchars($quote_id) ?><br>
    <strong>Total Estimated Value:</strong> <?= htmlspecialchars($amount) ?>
</div>

<p>Please review the details of your quote and the attached PDF. You can approve or decline this quote directly from your customer portal.</p>
<center><a href="<?= htmlspecialchars($action_link) ?>" class="btn">Review Quote</a></center>
