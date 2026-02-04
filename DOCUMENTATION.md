# AutoPartsOnline (AutoHub) — Project Documentation

Repository: `NimnaOfficial/AutoPartsOnline`  
Tech Stack: **PHP (procedural, no framework)** + **MySQL/MariaDB (mysqli)** + **CSS**  
Database: `auto_parts_db` (phpMyAdmin dump provided by you)  
Main idea: A simple online auto parts shop with:
- Product catalog + search
- Product details page
- Cart
- Checkout + simulated card payment
- Order history in profile
- Contact form → messages inbox for admin
- Admin dashboard for product CRUD + order/message management

---

## 1) How to run this project (Local Setup)

### 1.1 Requirements
- PHP 8.x (your SQL dump shows PHP 8.2.12)
- MariaDB/MySQL (your dump shows MariaDB 10.4.32)
- A local server environment:
  - XAMPP / WAMP / Laragon, or
  - Apache + PHP + MySQL configured manually

### 1.2 Database setup
1. Create the database:
   - `auto_parts_db`
2. Import your SQL dump:
   - `auto_parts_db.sql` using phpMyAdmin or CLI.
3. Ensure MySQL/MariaDB is running on **port 3307** (important):
   - The code uses port **3307** in `php/db_conn.php`.
   - If your DB uses 3306, you must change it in `php/db_conn.php`.

### 1.3 Configure DB connection
File: `php/db_conn.php`  
Key settings:
- host: `localhost`
- user: `root`
- password: `""` (empty)
- dbname: `auto_parts_db`
- port: `3307`

> Production note: credentials should be environment variables, not committed.

---

## 2) Database Documentation (from your `auto_parts_db.sql`)

### 2.1 Entity Relationship Overview
- `users` (1) — (many) `cart`
- `products` (1) — (many) `cart`
- `users` (1) — (many) `orders`
- `products` (1) — (many) `orders`
- `users` (1) — (many) `user_cars` **(not used in code)**

### 2.2 Tables

#### A) `users`
Stores registered users.

Columns:
- `id` (PK, int, auto-increment)
- `full_name` (varchar)
- `email` (varchar)
- `phone` (varchar)
- `address` (text)
- `username` (varchar, required)
- `password` (varchar, required)
- `role` (enum: `admin` | `customer`, default `customer`)

Used by code:
- `php/auth.php` (register/login)
- `php/profile.php` (profile update + display)
- `php/admin_orders.php` (order view with customer name/address via join)

**Critical mismatch (DB vs current repo code):**
- Your dump contains bcrypt hashes in `users.password` (strings like `$2y$10$...`).
- But the repo code in `php/auth.php` compares passwords in plain text:
  - `if ($password === $row['password'])`
- Correct bcrypt verification should use `password_verify($password, $row['password'])`.
- Similarly, registration should store hashes with `password_hash()`.

So: with the DB you provided, **login will fail unless you fix auth.php** or change the DB passwords back to plain text (not recommended).

---

#### B) `products`
Catalog of products.

Columns:
- `id` (PK)
- `name` (varchar, required)
- `description` (varchar 1000)
- `price` (decimal(10,2))
- `image_url` (varchar 255) — can be external URL or local filename
- `stock_quantity` (int)
- `created_at` (timestamp)

Used by code:
- `php/shop.php` (list/search)
- `php/product_view.php` (single product)
- `php/cart.php` (join to show product info)
- `php/checkout.php` (buy now / from cart)
- `php/process_payment.php` (reduce stock)
- `php/admin.php` (list and delete)
- `php/save_product.php` (insert)
- `php/edit_product.php` (update)
- `php/admin_orders.php` (join to show product name)

---

#### C) `cart`
Cart items per user.

Columns:
- `id` (PK)
- `user_id` (FK → users.id)
- `product_id` (FK → products.id)
- `quantity` (int, default 1)

Used by code:
- `php/add_to_cart.php` (insert/update quantity)
- `php/cart.php` (list + remove items)
- `php/checkout.php` (read cart items for checkout)
- `php/process_payment.php` (clear cart after purchase)

---

#### D) `orders`
Stores purchased items. **Design detail**: one row per purchased product.

Columns:
- `id` (PK)
- `user_id` (FK → users.id)
- `product_id` (FK → products.id)
- `quantity` (int, default 1)
- `total_price` (decimal(10,2))
- `status` (varchar, default `Pending`)
- `order_date` (timestamp, default now)

Used by code:
- `php/process_payment.php` (insert “Paid” rows, reduce stock)
- `php/profile.php` (order history)
- `php/admin_orders.php` (view and update status)

---

#### E) `messages`
Stores messages from contact form.

Columns:
- `id` (PK)
- `name` (varchar)
- `email` (varchar)
- `subject` (varchar)
- `message` (text)
- `submitted_at` (timestamp)
- `created_at` (timestamp)

Used by code:
- `php/contact.php` (insert message)
- `php/admin_messages.php` (list + delete)

---

#### F) `user_cars`
Stores cars linked to users.

Columns:
- `id` (PK)
- `user_id` (FK → users.id)
- `make`, `model` (varchar)
- `year` (int)
- `plate_number` (varchar)

**Not used in repo code**. This is either a future feature or leftover table.

---

## 3) Application Architecture

### 3.1 Style
- Pure PHP pages act as both controller + view (no MVC framework).
- Each page often:
  1. `session_start()`
  2. `include 'db_conn.php'`
  3. runs SQL queries
  4. outputs HTML

### 3.2 Sessions (authentication state)
Main session keys used:
- `user_id` — numeric ID from `users.id`
- `username` — username string
- `role` — `admin` or `customer`
- `admin_logged_in` — boolean used by admin pages

Checkout/payment session keys:
- `pending_order_items` — array of items prepared in checkout
- `pending_grand_total` — numeric total
- `is_cart_order` — boolean indicating checkout came from cart

---

## 4) Feature Flows (end-to-end)

### 4.1 User Registration & Login
Files:
- `php/login.php` — UI forms
- `php/auth.php` — DB logic + session set
- `php/logout.php` — destroys session

Register flow:
1. User fills register form on `login.php`
2. POST → `auth.php` with `register_user`
3. `auth.php` validates + inserts into `users`
4. Redirects back to `login.php?success=...`

Login flow:
1. User fills login form
2. POST → `auth.php` with `login_user`
3. `auth.php` reads user and verifies password
4. Sets session variables
5. Redirect:
   - admin → `admin.php`
   - customer → `../index.php`

---

### 4.2 Browse Products (Catalog + Search)
Files:
- `php/shop.php`
- `php/product_view.php`

Catalog:
- Lists all products ordered by newest (`id DESC`).

Search:
- Uses `?search=term`
- SQL filters product `name` using `LIKE`.

Product view:
- Uses `?id=product_id`
- Shows details and actions:
  - Add to Cart (if logged in)
  - Buy Now (if logged in)
  - Login to Order (if guest)

---

### 4.3 Cart
Files:
- `php/add_to_cart.php`
- `php/cart.php`

Add to cart:
- POST from product view to `add_to_cart.php`.
- If cart row exists for (user_id, product_id), increments quantity.
- Else inserts new cart row.

Cart page:
- Lists cart items via join.
- Remove via `cart.php?remove=cart_id`.
- Shows grand total.
- Proceed to checkout via `checkout.php?from_cart=true`.

---

### 4.4 Checkout + Payment (Simulated)
Files:
- `php/checkout.php`
- `php/payment_gateway.php`
- `php/process_payment.php`

Checkout supports:
- Buy now: `checkout.php?id=product_id`
- From cart: `checkout.php?from_cart=true`

On “Proceed to Card Payment”:
- `checkout.php` stores items and totals in session and redirects.

Payment gateway:
- Only accessible when session has pending items.
- Displays a fake card form.

Process payment:
- Inserts rows into `orders` with status `Paid`.
- Reduces stock in `products`.
- Clears cart if order was from cart.
- Redirects to profile.

---

### 4.5 Profile (Edit + Order History)
File:
- `php/profile.php`

Edit profile:
- Updates `users` via prepared statement.

Order history:
- Reads `orders` joined with `products`.
- Shows date, item name, total, status.

---

### 4.6 Contact & Messages
Files:
- `php/contact.php`
- `php/admin_messages.php`

Contact:
- Inserts into `messages`.

Admin messages:
- Lists messages newest-first.
- Deletes messages by `delete_id`.

---

### 4.7 Admin
Files:
- `php/admin.php`
- `php/save_product.php`
- `php/edit_product.php`
- `php/admin_orders.php`
- `php/admin_messages.php`

Admin login:
- Implemented inside `admin.php` with **hardcoded credentials** (`admin` / `123`).
- Not connected to `users` table.

Admin product management:
- Add: `save_product.php` inserts into `products` (prepared statement).
- Edit: `edit_product.php` updates `products`.
- Delete: `admin.php?delete_id=<product_id>` deletes product.

Admin orders management:
- `admin_orders.php` shows all orders with customer details.
- Can update `orders.status` (Pending/Shipped/Completed/Cancelled).

Admin messages management:
- `admin_messages.php` lists and deletes messages.

---

## 5) File-by-file Documentation

## 5.1 Root PHP

### `index.php`
**Responsibility**
- Homepage + navbar login/admin visibility.

**Uses**
- Session: `username`, `role`
- Includes DB conn but does not query.

---

## 5.2 PHP files (`/php`)

### `php/db_conn.php`
**Responsibility**
- Creates `$conn` MySQL connection.

**Tables**
- None directly; supports all DB operations.

---

### `php/login.php`
**Responsibility**
- Login/Register UI.

**Inputs**
- GET `error`, `success` for messages.

**Outputs**
- Forms POST to `auth.php`.

---

### `php/auth.php`
**Responsibility**
- Register + Login handlers.

**Register**
- Inserts into `users`.
- Checks duplicates in `users`.

**Login**
- Reads from `users`.
- Sets session.

**Important**
- Must match your DB password format (bcrypt recommended).

---

### `php/logout.php`
**Responsibility**
- Clears session and redirects home.

---

### `php/shop.php`
**Responsibility**
- Product listing + search.

**DB**
- `products`

**Input**
- GET `search`

**Output**
- Links to `product_view.php?id=...`

---

### `php/product_view.php`
**Responsibility**
- Show single product details.

**DB**
- `products`

**Inputs**
- GET `id`
- Session `username`

**Actions**
- POST to `add_to_cart.php`
- GET to `checkout.php?id=...`

---

### `php/add_to_cart.php`
**Responsibility**
- Add/increment cart item.

**DB**
- `cart`

**Inputs**
- Session `user_id`
- POST `product_id`

**Output**
- Redirect to `cart.php`

---

### `php/cart.php`
**Responsibility**
- Show cart + remove items + total.

**DB**
- `cart` JOIN `products`
- DELETE from `cart`

**Inputs**
- Session `user_id`
- GET `remove`

**Output**
- Link to `checkout.php?from_cart=true`

---

### `php/checkout.php`
**Responsibility**
- Build order summary (buy now or from cart)
- Save pending order to session

**DB**
- Buy now: reads `products`
- From cart: reads `cart` JOIN `products`

**Session writes**
- `pending_order_items`, `pending_grand_total`, `is_cart_order`

**Output**
- Redirects to `payment_gateway.php`

---

### `php/payment_gateway.php`
**Responsibility**
- Payment form UI (simulation)

**Inputs**
- Session `pending_order_items`, `pending_grand_total`

**Output**
- POST to `process_payment.php`

---

### `php/process_payment.php`
**Responsibility**
- Finalize order:
  - insert orders
  - reduce stock
  - clear cart (if needed)
  - cleanup session

**DB**
- `orders` INSERT
- `products` UPDATE (reduce stock)
- `cart` DELETE

---

### `php/profile.php`
**Responsibility**
- Profile update + order history

**DB**
- `users` UPDATE/SELECT (prepared)
- `orders` JOIN `products` (prepared)

---

### `php/contact.php`
**Responsibility**
- Contact page + insert message

**DB**
- `messages` INSERT

---

### `php/admin.php`
**Responsibility**
- Admin login + product list + add form + delete action

**DB**
- `products` SELECT
- `products` DELETE (by delete_id)

**Admin auth**
- Hardcoded credentials (not DB users)

---

### `php/save_product.php`
**Responsibility**
- Insert new product

**DB**
- `products` INSERT (prepared)

---

### `php/edit_product.php`
**Responsibility**
- Edit product page

**DB**
- `products` SELECT (by id)
- `products` UPDATE (prepared)

---

### `php/admin_orders.php`
**Responsibility**
- Admin orders management + status update

**DB**
- SELECT join: `orders` + `users` + `products`
- UPDATE: `orders.status`

---

### `php/admin_messages.php`
**Responsibility**
- Admin message inbox + delete

**DB**
- `messages` SELECT
- `messages` DELETE

---

## 5.3 CSS files (`/css`)
CSS is presentation-only (no server logic) but mapped to pages:

- `css/global.css` — shared theme/navbar/buttons
- `css/home.css` — homepage styles (`index.php`)
- `css/shop.css` — catalog grid (`php/shop.php`)
- `css/product.css` — product detail (`php/product_view.php`)
- `css/cart.css` — cart table (`php/cart.php`)
- `css/checkout.css` — checkout summary (`php/checkout.php`)
- `css/login.css` — login/register UI (`php/login.php`)
- `css/profile.css` — profile UI (`php/profile.php`)
- `css/contact.css` — contact UI (`php/contact.php`)
- `css/admin.css` — admin dashboard UI (`php/admin*.php`, `edit_product.php`)

---

## 6) Important Issues / Improvements (based on code + your DB)

### 6.1 Password handling mismatch (must fix if using your DB)
Your DB uses bcrypt hashes. Your code uses plain-text compare.
Fix needed:
- Registration: store hash with `password_hash()`
- Login: verify with `password_verify()`

### 6.2 SQL Injection Risks
Some pages build SQL using raw GET/POST variables (not prepared), e.g.:
- `shop.php` search
- `product_view.php` product id
- `add_to_cart.php` inserts/updates
- `contact.php` message insert
- admin delete and order status update

Recommended:
- Use prepared statements everywhere.

### 6.3 Cart delete ownership check
`cart.php?remove=id` deletes by cart id without verifying that row belongs to logged-in user.
Recommended:
- delete where `id=? AND user_id=?`

### 6.4 Payment processing consistency
`process_payment.php` inserts orders and reduces stock without DB transaction.
Recommended:
- Use SQL transaction:
  - BEGIN
  - check stock
  - insert orders
  - update stock
  - clear cart
  - COMMIT / ROLLBACK on error

### 6.5 Unused table `user_cars`
Database has `user_cars` but app does not use it.
Options:
- implement “My Cars” feature in profile, or
- remove the table if not needed.

### 6.6 Admin authentication
Admin login is hardcoded and not linked to `users`.
Recommended:
- Create admin users in `users` table and authenticate via `auth.php` with role admin.

---

## 7) Quick Reference: URL Routes

Public:
- `/index.php` — home
- `/php/shop.php` — catalog
- `/php/product_view.php?id=...` — product page
- `/php/login.php` — login/register
- `/php/contact.php` — contact

Customer (must be logged in):
- `/php/cart.php` — cart
- `/php/checkout.php?id=...` — buy now
- `/php/checkout.php?from_cart=true` — checkout cart
- `/php/payment_gateway.php` — payment UI (only after checkout)
- `/php/profile.php` — profile + order history

Admin:
- `/php/admin.php` — admin dashboard
- `/php/admin_orders.php` — orders
- `/php/admin_messages.php` — messages
- `/php/edit_product.php?id=...` — edit product

---

## 8) Glossary of Key Session Variables
- `user_id`: logged in user database id
- `username`: display name in navbar
- `role`: `admin` or `customer`
- `admin_logged_in`: boolean, admin access gate
- `pending_order_items`: checkout items array stored in session
- `pending_grand_total`: numeric
- `is_cart_order`: boolean: if checkout originated from cart

---

## 9) What to learn next (for full mastery)
To fully understand the coding side, focus on:
1. PHP session lifecycle (`session_start`, `session_destroy`)
2. mysqli prepared statements (secure queries)
3. SQL joins (cart/orders with products/users)
4. Server-side validation (ids, quantity, stock)
5. Transaction handling in payment flow

---

End of Documentation