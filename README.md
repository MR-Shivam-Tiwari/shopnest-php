# ShopNest — PHP E-Commerce Store

A complete, original e-commerce store written in plain PHP 8 + SQLite: product catalog with search and categories, product pages, a session cart, cash-on-delivery checkout that saves real orders, and an admin panel to add products and view orders. No framework, no copied code — every file written from scratch so the whole system can be explained line by line.

## Features
- Catalog: seeded products, live search, category filters, discount % computed from MRP
- Product detail pages with stock and delivery info
- Session-based cart: add, remove, totals
- Checkout: customer details → order stored in the database with itemised contents
- Admin panel (demo login `admin` / `admin123`): add products, view all orders
- PDO prepared statements + escaped output throughout (SQL-injection / XSS safe)

## Run locally
```bash
php -S localhost:8000
# Store: http://localhost:8000 · Admin: http://localhost:8000/admin.php
```
Requires PHP 8+ with `pdo_sqlite`. The SQLite database and seed products are created automatically on first run. For MySQL, only the PDO DSN in `config.php` changes.

## Files
`config.php` (DB + seed + helpers) · `index.php` (catalog/search) · `product.php` (detail) · `cart.php` (cart + checkout + orders) · `admin.php` (login, product CRUD, order list) · `assets/style.css`

## Interview points
- How this maps to Laravel: routes/controllers ≈ the page files, Eloquent ≈ the PDO layer, Blade ≈ the templates with `e()` escaping; what Laravel adds (ORM, migrations, middleware, validation, queues).
- Security talking points: prepared statements, output escaping, why the demo admin password must become `password_hash` + a users table in production.
- Extension ideas: Razorpay payment integration, user accounts with order history, product images upload, discount coupons, REST API for a mobile app.
