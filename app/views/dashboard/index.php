<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h2>Dashboard</h2>
</div>

<div class="stats-grid">
    <div class="card stat-card">
        <h3><?php echo $totalProducts; ?></h3>
        <p>Total Products</p>
    </div>
    <div class="card stat-card">
        <h3><?php echo $totalCategories; ?></h3>
        <p>Total Categories</p>
    </div>
    <div class="card stat-card">
        <h3><?php echo $totalSuppliers; ?></h3>
        <p>Total Suppliers</p>
    </div>
    <div class="card stat-card warning">
        <h3><?php echo count($lowStockProducts); ?></h3>
        <p>Low Stock Items</p>
    </div>
</div>

<?php if(!empty($lowStockProducts)): ?>
<div class="card">
    <h3>Low Stock Alert</h3>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Current Stock</th>
                <th>Reorder Level</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($lowStockProducts as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product->name); ?></td>
                <td><?php echo $product->current_stock; ?></td>
                <td><?php echo $product->reorder_level; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>