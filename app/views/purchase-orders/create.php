<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h2>New Purchase Order</h2>
</div>

<div class="card" style="max-width: 900px;">
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>purchaseorders/create" method="POST">
        <div class="form-group">
            <label>Supplier</label>
            <select name="supplier_id" required>
                <option value="">-- Select Supplier --</option>
                <?php foreach($suppliers as $supplier): ?>
                    <option value="<?php echo $supplier->id; ?>"><?php echo htmlspecialchars($supplier->name); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ecf0f1;">

        <h3 style="margin-bottom: 15px;">Order Items</h3>

        <table id="itemsTable">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Unit Cost</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr class="item-row">
                    <td>
                        <select name="product_id[]" class="product-select" required>
                            <option value="">-- Select Product --</option>
                            <?php foreach($products as $product): ?>
                                <option value="<?php echo $product->id; ?>"><?php echo htmlspecialchars($product->name); ?> (<?php echo htmlspecialchars($product->sku); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" name="quantity[]" min="1" required></td>
                    <td><input type="number" name="unit_cost[]" step="0.01" min="0" required></td>
                    <td><button type="button" class="btn btn-danger remove-row">X</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" id="addRow" class="btn btn-warning" style="margin-top: 10px;">+ Add Row</button>

        <hr style="margin: 20px 0; border: none; border-top: 1px solid #ecf0f1;">

        <a href="<?php echo BASE_URL; ?>purchaseorders" class="btn btn-warning">Cancel</a>
        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>

<script>
document.getElementById('addRow').addEventListener('click', function() {
    const tbody = document.querySelector('#itemsTable tbody');
    const firstRow = document.querySelector('.item-row');
    const newRow = firstRow.cloneNode(true);

    // Clear values in the cloned row
    newRow.querySelectorAll('select, input').forEach(field => field.value = '');

    tbody.appendChild(newRow);
});

document.querySelector('#itemsTable tbody').addEventListener('click', function(e) {
    if(e.target.classList.contains('remove-row')) {
        const rows = document.querySelectorAll('.item-row');
        if(rows.length > 1) {
            e.target.closest('tr').remove();
        } else {
            alert('At least one item row is required');
        }
    }
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>