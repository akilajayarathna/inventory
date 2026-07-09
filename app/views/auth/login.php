<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Inventory System</title>
    <link rel="stylesheet" href="/inventory-system/public/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <h2>Inventory System</h2>
        <h3>Login</h3>

        <?php if(isset($error)): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo BASE_URL; ?>auth/login" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    placeholder="Enter your email"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                    required
                >
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password"
                    id="password" 
                    name="password" 
                    placeholder="Enter your password"
                    required
                >
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>