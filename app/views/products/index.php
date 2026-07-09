<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Products</h2>
    <a href="<?php echo BASE_URL; ?>products/create" class="btn btn-primary">+ Add Product</a>
</div>

<div class="card">
    <?php if(empty($products)): ?>
        <p>No products found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Category</th>
                <th>Supplier</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($products as $product): ?>
            <tr>
                <td><?php echo htmlspecialchars($product->sku); ?></td>
                <td><?php echo htmlspecialchars($product->name); ?></td>
                <td><?php echo htmlspecialchars($product->category_name ?? '-'); ?></td>
                <td><?php echo htmlspecialchars($product->supplier_name ?? '-'); ?></td>
                <td><?php echo number_format($product->unit_price, 2); ?></td>
                <td>
                    <?php if($product->current_stock <= $product->reorder_level): ?>
                        <span style="color:#e74c3c; font-weight:bold;"><?php echo $product->current_stock; ?></span>
                    <?php else: ?>
                        <?php echo $product->current_stock; ?>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="<?php echo BASE_URL; ?>products/edit/<?php echo $product->id; ?>" class="btn btn-warning">Edit</a>
                    <a href="<?php echo BASE_URL; ?>products/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>