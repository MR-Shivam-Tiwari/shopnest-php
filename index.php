<?php
require __DIR__ . '/config.php';
if (isset($_GET['add'])) {
    $id = (int) $_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: index.php?added=1'); exit;
}
$q = trim($_GET['q'] ?? ''); $cat = $_GET['cat'] ?? 'All';
$sql = 'SELECT * FROM products WHERE 1=1'; $params = [];
if ($q !== '') { $sql .= ' AND name LIKE ?'; $params[] = "%$q%"; }
if ($cat !== 'All') { $sql .= ' AND category = ?'; $params[] = $cat; }
$stmt = $pdo->prepare($sql); $stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
$cats = $pdo->query('SELECT DISTINCT category FROM products ORDER BY category')->fetchAll(PDO::FETCH_COLUMN);
$cartCount = array_sum($_SESSION['cart'] ?? []);
?>
<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>ShopNest — PHP E-Commerce Store</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><a class="logo" href="index.php">ShopNest</a>
<form class="search" method="get"><input name="q" value="<?= e($q) ?>" placeholder="Search products..."><button>Search</button></form>
<nav><a href="cart.php">Cart (<?= $cartCount ?>)</a> · <a href="admin.php">Admin</a></nav></header>
<?php if (isset($_GET['added'])): ?><p class="flash">Added to cart.</p><?php endif; ?>
<nav class="cats"><a href="index.php" class="<?= $cat==='All'?'on':'' ?>">All</a>
<?php foreach ($cats as $c): ?><a href="?cat=<?= urlencode($c) ?>" class="<?= $cat===$c?'on':'' ?>"><?= e($c) ?></a><?php endforeach; ?></nav>
<main class="grid">
<?php if (!$products): ?><p>No products found. Try a different search.</p><?php endif; ?>
<?php foreach ($products as $p): $off = $p['mrp'] > $p['price'] ? round((1 - $p['price']/$p['mrp']) * 100) : 0; ?>
<article class="card"><div class="thumb"><?= e($p['emoji']) ?></div>
<h3><a href="product.php?id=<?= (int)$p['id'] ?>"><?= e($p['name']) ?></a></h3>
<p class="cat"><?= e($p['category']) ?> · <?= $p['stock'] > 0 ? 'In stock' : 'Out of stock' ?></p>
<p class="price"><?= inr((int)$p['price']) ?> <?php if ($off): ?><s><?= inr((int)$p['mrp']) ?></s> <span class="off"><?= $off ?>% off</span><?php endif; ?></p>
<a class="btn" href="?add=<?= (int)$p['id'] ?>">Add to cart</a></article>
<?php endforeach; ?></main>
<footer>ShopNest · Original PHP + SQLite e-commerce demo · Built by Shivam Tiwari</footer>
</body></html>
