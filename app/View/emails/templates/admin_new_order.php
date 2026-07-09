<h2>New Order Received</h2>
<p>A new order has been placed on the store and payment has been successfully captured.</p>

<table class="data-table">
    <tr>
        <th>Order ID</th>
        <td><?= htmlspecialchars($order_id) ?></td>
    </tr>
    <tr>
        <th>Customer</th>
        <td><?= htmlspecialchars($customer_name) ?></td>
    </tr>
    <tr>
        <th>Value</th>
        <td><?= htmlspecialchars($amount) ?></td>
    </tr>
</table>

<center><a href="https://marigoldsignature.com/admin/orders/<?= htmlspecialchars($order_id) ?>" class="btn">Process Order</a></center>
