<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="/inventory-system/public/css/style.css">
</head>
<body>
    <div class="wrapper">
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3>Inventory System</h3>
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?php echo BASE_URL; ?>dashboard">Dashboard</a></li>
                <li><a href="<?php echo BASE_URL; ?>products">Products</a></li>
                <li><a href="<?php echo BASE_URL; ?>categories">Categories</a></li>
                <li><a href="<?php echo BASE_URL; ?>suppliers">Suppliers</a></li>
                <li><a href="<?php echo BASE_URL; ?>stock">Stock</a></li>
                <li><a href="<?php echo BASE_URL; ?>purchaseorders">Purchase Orders</a></li>
            </ul>
            <div class="sidebar-footer">
                <span>👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="<?php echo BASE_URL; ?>auth/logout">Logout</a>
            </div>
        </nav>
        <div class="main-content">