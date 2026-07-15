<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h2>Adjust Stock</h2>
</div>

<div class="card" style="max-width: 600px;">
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>stock/adjust" method="POST">
        <div class="form-group">
            <label>Product</label>
            <select name="product_id" required>
                <option value="">-- Select Product --</option>
                <?php foreach($products as $product): ?>
                    <option value="<?php echo $product->id; ?>">
                        <?php echo htmlspecialchars($product->name); ?> (<?php echo htmlspecialchars($product->sku); ?>) — Current: <?php echo $product->current_stock; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Movement Type</label>
            <select name="type" required>
                <option value="">-- Select Type --</option>
                <option value="adjustment_in">Stock In (Add)</option>
                <option value="adjustment_out">Stock Out (Remove)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Quantity</label>
            <input type="number" name="quantity" min="1" required>
        </div>

        <div class="form-group">
            <label>Note</label>
            <textarea name="note" rows="3" placeholder="Reason for adjustment (optional)"></textarea>
        </div>

        <a href="<?php echo BASE_URL; ?>stock" class="btn btn-warning">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>