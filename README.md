# 🏎️ AutoHub - Premium Auto Parts E-Commerce

![AutoHub Banner](https://i.ibb.co/zhqDrX8W/autohub-1.png)

> AutoHub is a responsive e-commerce platform delivering premium auto parts in Sri Lanka. It combines an intuitive UI/UX with a secure PHP backend for a seamless shopping experience.

---

## 📖 Project Overview
Developed as a comprehensive web application for coursework, AutoHub bridges the gap between automotive enthusiasts and high-quality parts. The platform features a dynamic product catalog, user authentication, a personalized profile dashboard, and an administrative control panel. 

The architecture is built with a strong focus on **Software Engineering principles** (modular code, prepared SQL statements) and **UI/UX design** (clean navigation, distinct visual hierarchy, and interactive elements).

---

## ✨ Key Features

### 🎨 Frontend (UI/UX)
* **Modern Interface:** High-contrast, dark-themed UI designed for automotive aesthetics.
* **Interactive Elements:** Hover states, micro-interactions, and smooth transitions.
* **Responsive Layout:** Desktop-optimized experience with CSS Flexbox and Grid layouts.
* **Clear Navigation:** Sticky top navigation bar for seamless user journey tracking.

### ⚙️ Backend (Software Engineering)
* **Role-Based Access Control (RBAC):** Distinct routing and privileges for `admin` and `customer` accounts.
* **Secure Database Transactions:** Use of PHP Prepared Statements (`bind_param`) to prevent SQL Injection attacks.
* **Session Management:** Secure tracking of user states across the shopping and checkout experience.
* **Dynamic Data Rendering:** Real-time fetching of order history and product listings.

---

## 🛠️ Tech Stack
* **Frontend:** HTML5, CSS3, Vanilla JavaScript, FontAwesome Icons.
* **Backend:** Core PHP (Procedural/OOP mix).
* **Database:** MySQL (Relational Database Management System).
* **Deployment:** GitHub (Version Control) & InfinityFree (Live Hosting).

---

## 📂 Folder Structure
```text
AutoHub/
│
├── css/
│   ├── global.css      # Core styles, variables, resets
│   ├── home.css        # Landing page specific styles
│   └── profile.css     # User dashboard styles
│
├── php/
│   ├── db_conn.php     # Database configuration (DO NOT COMMIT CREDENTIALS)
│   ├── login.php       # Authentication logic
│   ├── register.php    # User creation
│   ├── profile.php     # User dashboard & order history
│   ├── shop.php        # Dynamic product catalog
│   ├── contact.php     # User inquiry form
│   └── logout.php      # Session termination
│
├── index.php           # Main landing page
├── README.md           # Project documentation
└── auto_parts_db.sql   # Database schema & dummy data
```
## 🚀 Installation & Setup

### Local Development (XAMPP/WAMP)
1. Clone the repository: `git clone https://github.com/NimnaOfficial/OnlineAutoHub.git`
2. Move the project folder to your local server directory (e.g., `C:\xampp\htdocs\AutoHub`).
3. Open `phpMyAdmin` and create a new database named `auto_parts_db`.
4. Import the provided `auto_parts_db.sql` file into the new database.
5. Update `php/db_conn.php` with your local credentials:
   ```php
   $servername = "localhost:3307"; // Update port if necessary
   $username = "root";
   $password = "";
   $dbname = "auto_parts_db";
   ```
6. Access the site via <code>http://localhost/AutoHub/index.php</code>.

**Live Deployment (InfinityFree)**
 1. Upload the project files (excluding <code>.git</code> and <code>.sql</code> files) to the <code>htdocs</code> directory via FTP or File Manager.
 2. Create a MySQL database in the hosting control panel.
 3. Import the <code>.sql</code> file via the host's phpMyAdmin.
 4. Update <code>php/db_conn.php</code> with the live database credentials provided by the host.

## 🔒 Security Note (Coursework Context)
* Note for evaluators: For the specific requirements of this coursework iteration, user passwords are currently stored in plain text to demonstrate direct database insertion and retrieval mapping. In a production environment, this would be upgraded to utilize <code>password_hash()</code> and <code>password_verify()</code> with bcrypt encryption. Prepared statements are actively implemented to mitigate SQLi vulnerabilities.

##
> Developed for NIBM Coursework - 2026
