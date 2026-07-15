<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Purchase Orders</h2>
    <a href="<?php echo BASE_URL; ?>purchaseorders/create" class="btn btn-primary">+ New Purchase Order</a>
</div>

<div class="card">
    <?php if(empty($orders)): ?>
        <p>No purchase orders found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Supplier</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Ordered At</th>
                <th>Ordered By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
                <td>#<?php echo $order->id; ?></td>
                <td><?php echo htmlspecialchars($order->supplier_name ?? '-'); ?></td>
                <td><?php echo number_format($order->total_amount, 2); ?></td>
                <td>
                    <?php if($order->status == 'pending'): ?>
                        <span style="color:#f39c12; font-weight:bold;">Pending</span>
                    <?php elseif($order->status == 'received'): ?>
                        <span style="color:#2ecc71; font-weight:bold;">Received</span>
                    <?php else: ?>
                        <span style="color:#e74c3c; font-weight:bold;">Cancelled</span>
                    <?php endif; ?>
                </td>
                <td><?php echo date('Y-m-d H:i', strtotime($order->ordered_at)); ?></td>
                <td><?php echo htmlspecialchars($order->user_name ?? '-'); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>purchaseorders/viewOrder/<?php echo $order->id; ?>" class="btn btn-primary">View</a>
                    <?php if($order->status == 'pending'): ?>
                        <a href="<?php echo BASE_URL; ?>purchaseorders/receive/<?php echo $order->id; ?>" class="btn btn-success" onclick="return confirm('Mark this order as received? This will update stock levels.')">Receive</a>
                        <a href="<?php echo BASE_URL; ?>purchaseorders/cancel/<?php echo $order->id; ?>" class="btn btn-danger" onclick="return confirm('Cancel this order?')">Cancel</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>