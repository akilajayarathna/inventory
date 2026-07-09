<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Suppliers</h2>
    <a href="<?php echo BASE_URL; ?>suppliers/create" class="btn btn-primary">+ Add Supplier</a>
</div>

<div class="card">
    <?php if(empty($suppliers)): ?>
        <p>No suppliers found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact Person</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($suppliers as $supplier): ?>
            <tr>
                <td><?php echo $supplier->id; ?></td>
                <td><?php echo htmlspecialchars($supplier->name); ?></td>
                <td><?php echo htmlspecialchars($supplier->contact_person); ?></td>
                <td><?php echo htmlspecialchars($supplier->phone); ?></td>
                <td><?php echo htmlspecialchars($supplier->email); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>suppliers/edit/<?php echo $supplier->id; ?>" class="btn btn-warning">Edit</a>
                    <a href="<?php echo BASE_URL; ?>suppliers/delete/<?php echo $supplier->id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>