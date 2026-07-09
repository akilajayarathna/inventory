<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h2>Edit Product</h2>
</div>

<div class="card" style="max-width: 700px;">
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>products/edit/<?php echo $product->id; ?>" method="POST">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product->name); ?>" required>
        </div>

        <div class="form-group">
            <label>SKU</label>
            <input type="text" name="sku" value="<?php echo htmlspecialchars($product->sku); ?>" required>
        </div>

        <div class="form-group">
            <label>Category</label>
            <select name="category_id" required>
                <option value="">-- Select Category --</option>
                <?php foreach($categories as $category): ?>
                    <option value="<?php echo $category->id; ?>" <?php echo ($category->id == $product->category_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id">
                <option value="">-- Select Supplier --</option>
                <?php foreach($suppliers as $supplier): ?>
                    <option value="<?php echo $supplier->id; ?>" <?php echo ($supplier->id == $product->supplier_id) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($supplier->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?php echo htmlspecialchars($product->description); ?></textarea>
        </div>

        <div class="form-group">
            <label>Unit Price</label>
            <input type="number" step="0.01" name="unit_price" value="<?php echo $product->unit_price; ?>" required>
        </div>

        <div class="form-group">
            <label>Reorder Level</label>
            <input type="number" name="reorder_level" value="<?php echo $product->reorder_level; ?>" required>
        </div>

        <a href="<?php echo BASE_URL; ?>products" class="btn btn-warning">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>