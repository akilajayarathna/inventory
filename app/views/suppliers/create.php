<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="page-header">
    <h2>Add Supplier</h2>
</div>

<div class="card" style="max-width: 600px;">
    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?php echo BASE_URL; ?>suppliers/create" method="POST">
        <div class="form-group">
            <label>Supplier Name</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email">
        </div>
        <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3"></textarea>
        </div>
        <a href="<?php echo BASE_URL; ?>suppliers" class="btn btn-warning">Cancel</a>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>