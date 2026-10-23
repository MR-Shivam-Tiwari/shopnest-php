<?php
require __DIR__ . '/config.php';
// Demo admin: username admin / password admin123 (change before real use; stored as a hash check in real apps)
if (isset($_GET['logout'])) { unset($_SESSION['admin']); header('Location: admin.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'login') {
    if (($_POST['user'] ?? '') === 'admin' && ($_POST['pass'] ?? '') === 'admin123') $_SESSION['admin'] = true;
}
if (!empty($_SESSION['admin']) && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_product') {
    $stmt = $pdo->prepare('INSERT INTO products (name, category, price, mrp, emoji, description, stock) VALUES (?,?,?,?,?,?,?)');
    $stmt->execute([trim($_POST['name']), trim($_POST['category']), (int)$_POST['price'], (int)$_POST['mrp'], trim($_POST['emoji']) ?: '📦', trim($_POST['description']), (int)$_POST['stock']]);
    header('Location: admin.php'); exit;
}
$orders = !empty($_SESSION['admin']) ? $pdo->query('SELECT * FROM orders ORDER BY id DESC')->fetchAll(PDO::FETCH_ASSOC) : [];
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin — ShopNest</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><a class="logo" href="index.php">ShopNest</a><nav><a href="admin.php?logout=1">Logout</a></nav></header>
<main class="wrap"><h1>Admin panel</h1>
<?php if (empty($_SESSION['admin'])): ?>
<form method="post" class="checkout"><input type="hidden" name="action" value="login">
<input name="user" placeholder="Username (demo: admin)" required><input name="pass" type="password" placeholder="Password (demo: admin123)" required>
<button class="btn">Login</button></form>
<?php else: ?>
<h3>Add product</h3>
<form method="post" class="checkout"><input type="hidden" name="action" value="add_product">
<input name="name" placeholder="Product name" required><input name="category" placeholder="Category" required>
<input name="price" type="number" placeholder="Price (₹)" required><input name="mrp" type="number" placeholder="MRP (₹)">
<input name="emoji" placeholder="Emoji icon"><input name="stock" type="number" placeholder="Stock" value="10">
<textarea name="description" placeholder="Description"></textarea><button class="btn">Add product</button></form>
<h3>Orders (<?= count($orders) ?>)</h3>
<?php foreach ($orders as $o): ?><p>#<?= (int)$o['id'] ?> · <?= e($o['customer']) ?> (<?= e($o['phone']) ?>) — <?= inr((int)$o['total']) ?><br><small><?= e($o['items']) ?> · <?= e($o['address']) ?></small></p><?php endforeach; ?>
<?php if (!$orders): ?><p>No orders yet.</p><?php endif; ?>
<?php endif; ?></main></body></html>
