<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center;">
    <h2>Categories</h2>
    <a href="<?php echo BASE_URL; ?>categories/create" class="btn btn-primary">+ Add Category</a>
</div>

<div class="card">
    <?php if(empty($categories)): ?>
        <p>No categories found.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categories as $category): ?>
            <tr>
                <td><?php echo $category->id; ?></td>
                <td><?php echo htmlspecialchars($category->name); ?></td>
                <td><?php echo htmlspecialchars($category->description); ?></td>
                <td>
                    <a href="<?php echo BASE_URL; ?>categories/edit/<?php echo $category->id; ?>" class="btn btn-warning">Edit</a>
                    <a href="<?php echo BASE_URL; ?>categories/delete/<?php echo $category->id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>