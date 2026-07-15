<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Stock Movements</h2>
    <a href="<?php echo BASE_URL; ?>stock/adjust" class="btn btn-primary">+ Adjust Stock</a>
</div>

<div class="card">
    <?php if(empty($movements)): ?>
        <p>No stock movements found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Product</th>
                <th>Type</th>
                <th>Quantity</th>
                <th>Note</th>
                <th>By</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($movements as $movement): ?>
            <tr>
                <td><?php echo date('Y-m-d H:i', strtotime($movement->created_at)); ?></td>
                <td><?php echo htmlspecialchars($movement->product_name ?? 'Unknown'); ?> (<?php echo htmlspecialchars($movement->sku ?? '-'); ?>)</td>
                <td><?php echo ucfirst($movement->type); ?></td>
                <td>
                    <?php if($movement->quantity > 0): ?>
                        <span style="color:#2ecc71; font-weight:bold;">+<?php echo $movement->quantity; ?></span>
                    <?php else: ?>
                        <span style="color:#e74c3c; font-weight:bold;"><?php echo $movement->quantity; ?></span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($movement->note ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($movement->user_name ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>