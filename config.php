<?php
// ShopNest — original PHP e-commerce store. PDO + SQLite, sessions for cart/admin.
declare(strict_types=1);
session_start();
$pdo = new PDO('sqlite:' . __DIR__ . '/shopnest.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT NOT NULL, category TEXT NOT NULL,
  price INTEGER NOT NULL, mrp INTEGER NOT NULL DEFAULT 0, emoji TEXT NOT NULL DEFAULT '📦',
  description TEXT NOT NULL DEFAULT '', stock INTEGER NOT NULL DEFAULT 10)");
$pdo->exec("CREATE TABLE IF NOT EXISTS orders (
  id INTEGER PRIMARY KEY AUTOINCREMENT, customer TEXT NOT NULL, phone TEXT NOT NULL,
  address TEXT NOT NULL, items TEXT NOT NULL, total INTEGER NOT NULL,
  created_at TEXT NOT NULL DEFAULT (datetime('now')))");
if ((int)$pdo->query('SELECT COUNT(*) FROM products')->fetchColumn() === 0) {
    $seed = [
        ['Wireless Noise-Cancelling Headphones','Audio',2999,4999,'🎧','40h battery, Bluetooth 5.3, deep bass with active noise cancellation.',24],
        ['Mechanical Gaming Keyboard','Accessories',3499,5999,'⌨️','Hot-swappable switches, RGB backlight, aluminium frame.',18],
        ['Smart Fitness Watch','Wearables',4499,7999,'⌚','AMOLED display, SpO2 + heart-rate tracking, 14-day battery.',30],
        ['USB-C Fast Charger 65W','Accessories',1299,2199,'🔌','GaN charger, charges a laptop and two phones at once.',50],
        ['Ergonomic Office Chair','Furniture',8999,13999,'🪑','Lumbar support, breathable mesh, 3-year warranty.',7],
        ['4K Action Camera','Cameras',11999,16999,'📷','Waterproof to 10m, stabilisation, 4K60 video.',12],
        ['Portable Bluetooth Speaker','Audio',1999,3499,'🔊','IPX7 waterproof, 18h playtime, punchy stereo sound.',40],
        ['Laptop Stand Aluminium','Accessories',1499,2499,'💻','Foldable, 6 height levels, fits 10-17 inch laptops.',35],
    ];
    $stmt = $pdo->prepare('INSERT INTO products (name, category, price, mrp, emoji, description, stock) VALUES (?,?,?,?,?,?,?)');
    foreach ($seed as $row) $stmt->execute($row);
}
function inr(int $n): string { return '₹' . number_format($n); }
function e(string $s): string { return htmlspecialchars($s, ENT_QUOTES); }
