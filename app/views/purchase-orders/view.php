<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Purchase Order #<?php echo $order->id; ?></h2>
    <a href="<?php echo BASE_URL; ?>purchaseorders" class="btn btn-warning">Back</a>
</div>

<div class="card">
    <p><strong>Supplier:</strong> <?php echo htmlspecialchars($order->supplier_name ?? '-'); ?></p>
    <p><strong>Status:</strong>
        <?php if($order->status == 'pending'): ?>
            <span style="color:#f39c12; font-weight:bold;">Pending</span>
        <?php elseif($order->status == 'received'): ?>
            <span style="color:#2ecc71; font-weight:bold;">Received</span>
        <?php else: ?>
            <span style="color:#e74c3c; font-weight:bold;">Cancelled</span>
        <?php endif; ?>
    </p>
    <p><strong>Ordered At:</strong> <?php echo date('Y-m-d H:i', strtotime($order->ordered_at)); ?></p>
    <?php if($order->received_at): ?>
        <p><strong>Received At:</strong> <?php echo date('Y-m-d H:i', strtotime($order->received_at)); ?></p>
    <?php endif; ?>
    <p><strong>Ordered By:</strong> <?php echo htmlspecialchars($order->user_name ?? '-'); ?></p>
</div>

<div class="card">
    <h3 style="margin-bottom: 15px;">Order Items</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Quantity</th>
                <th>Unit Cost</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?php echo htmlspecialchars($item->product_name ?? 'Unknown'); ?></td>
                <td><?php echo htmlspecialchars($item->sku ?? '-'); ?></td>
                <td><?php echo $item->quantity; ?></td>
                <td><?php echo number_format($item->unit_cost, 2); ?></td>
                <td><?php echo number_format($item->quantity * $item->unit_cost, 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;"><strong>Total</strong></td>
                <td><strong><?php echo number_format($order->total_amount, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php if($order->status == 'pending'): ?>
<div class="card">
    <a href="<?php echo BASE_URL; ?>purchaseorders/receive/<?php echo $order->id; ?>" class="btn btn-success" onclick="return confirm('Mark this order as received? This will update stock levels.')">Mark as Received</a>
    <a href="<?php echo BASE_URL; ?>purchaseorders/cancel/<?php echo $order->id; ?>" class="btn btn-danger" onclick="return confirm('Cancel this order?')">Cancel Order</a>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>