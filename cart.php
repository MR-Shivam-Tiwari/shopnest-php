<?php
require __DIR__ . '/config.php';
$cart = $_SESSION['cart'] ?? [];
if (isset($_GET['remove'])) { unset($cart[(int)$_GET['remove']]); $_SESSION['cart'] = $cart; header('Location: cart.php'); exit; }
$placed = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cart) {
    $items = []; $total = 0;
    foreach ($cart as $id => $qty) {
        $stmt = $pdo->prepare('SELECT name, price FROM products WHERE id = ?');
        $stmt->execute([(int)$id]); $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) { $items[] = $row['name'] . ' x' . $qty; $total += $row['price'] * $qty; }
    }
    $stmt = $pdo->prepare('INSERT INTO orders (customer, phone, address, items, total) VALUES (?,?,?,?,?)');
    $stmt->execute([trim($_POST['customer'] ?? ''), trim($_POST['phone'] ?? ''), trim($_POST['address'] ?? ''), implode('; ', $items), $total]);
    $_SESSION['cart'] = []; $cart = []; $placed = true; $placedTotal = $total;
}
$rows = []; $total = 0;
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([(int)$id]); $p = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($p) { $rows[] = [$p, $qty]; $total += $p['price'] * $qty; }
}
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Your Cart — ShopNest</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><a class="logo" href="index.php">ShopNest</a></header>
<main class="wrap"><h1>Your cart</h1>
<?php if ($placed): ?><p class="flash">Order placed successfully. Total paid on delivery: <?= inr($placedTotal) ?>. Thank you for shopping with ShopNest.</p><?php endif; ?>
<?php if (!$rows && !$placed): ?><p>Your cart is empty. <a href="index.php">Browse products</a></p><?php endif; ?>
<?php foreach ($rows as [$p, $qty]): ?>
<p><?= e($p['emoji']) ?> <?= e($p['name']) ?> — <?= inr((int)$p['price']) ?> × <?= $qty ?> = <b><?= inr((int)$p['price'] * $qty) ?></b> <a href="?remove=<?= (int)$p['id'] ?>">Remove</a></p>
<?php endforeach; ?>
<?php if ($rows): ?><h3>Total: <?= inr($total) ?></h3>
<form method="post" class="checkout"><input name="customer" placeholder="Full name" required>
<input name="phone" placeholder="Phone" required><textarea name="address" placeholder="Delivery address" required></textarea>
<button class="btn">Place order (Cash on Delivery)</button></form><?php endif; ?>
</main></body></html>
