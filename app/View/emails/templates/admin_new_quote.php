<h2>New Quote Request (Admin)</h2>
<p>A new quote request has been submitted by <?= htmlspecialchars($customer_name) ?>.</p>
<div class="summary-box">
    <strong>Reference:</strong> <?= htmlspecialchars($quote_id) ?><br>
    <strong>Date:</strong> <?= htmlspecialchars($date) ?>
</div>
<center><a href="https://marigoldsignature.com/admin/quotes/<?= htmlspecialchars($quote_id) ?>" class="btn">Review Request</a></center>
