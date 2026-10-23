<?php
require __DIR__ . '/config.php';
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([(int)($_GET['id'] ?? 0)]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$p) { http_response_code(404); exit('Product not found'); }
if (isset($_GET['add'])) { $_SESSION['cart'][$p['id']] = ($_SESSION['cart'][$p['id']] ?? 0) + 1; header('Location: cart.php'); exit; }
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($p['name']) ?> — ShopNest</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><a class="logo" href="index.php">ShopNest</a><nav><a href="cart.php">Cart</a></nav></header>
<main class="detail"><div class="thumb big"><?= e($p['emoji']) ?></div>
<div><h1><?= e($p['name']) ?></h1><p class="cat"><?= e($p['category']) ?></p>
<p><?= e($p['description']) ?></p>
<p class="price big"><?= inr((int)$p['price']) ?> <?php if ($p['mrp'] > $p['price']): ?><s><?= inr((int)$p['mrp']) ?></s><?php endif; ?></p>
<p><?= (int)$p['stock'] ?> in stock · Free delivery over ₹999</p>
<a class="btn" href="?id=<?= (int)$p['id'] ?>&add=1">Add to cart</a> <a href="index.php">Continue shopping</a></div></main>
</body></html>
