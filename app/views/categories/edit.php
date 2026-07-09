<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h2>Edit Category</h2>
</div>

<div class="card" style="max-width: 600px;">
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>categories/edit/<?php echo $category->id; ?>" method="POST">
        <div class="form-group">
            <label>Category Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($category->name); ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"><?php echo htmlspecialchars($category->description); ?></textarea>
        </div>
        <a href="<?php echo BASE_URL; ?>categories" class="btn btn-warning">Cancel</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>