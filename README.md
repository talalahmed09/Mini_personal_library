# 📚 Mini Library Management System

> A personal book tracking web app built with pure PHP and MySQL. No frameworks. Runs on XAMPP.

![PHP](https://img.shields.io/badge/PHP-procedural-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-database-4479A1?style=flat&logo=mysql&logoColor=white)
![XAMPP](https://img.shields.io/badge/XAMPP-compatible-FB7A24?style=flat&logo=xampp&logoColor=white)
![No Framework](https://img.shields.io/badge/Framework-none-green?style=flat)

---

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Tech Stack](#tech-stack)
- [Folder Structure](#folder-structure)
- [Setup & Installation](#setup--installation)
- [Database Schema](#database-schema)
- [Helper Functions](#helper-functions)
- [Security](#security)
- [How It Works](#how-it-works)
- [Common Errors](#common-errors)
- [Future Enhancements](#future-enhancements)

---

## Overview

A personal book tracking web application built with pure PHP and MySQL. Users can register, log in, and manage their own private collection of books. Each book has a title, author, category, reading status, type (online/offline), optional link, star rating, and chapter progress tracker.

Every user sees **only their own books** — complete data isolation enforced at the database level.

---

## Features

- 🔐 **Authentication** — Register, Login, Logout, Change Password
- 📚 **Book CRUD** — Add, Edit, Delete, View all books
- 📌 **Reading Status** — Want to Read / Reading / Completed
- 🌐 **Book Type** — Online (with clickable link) or Physical
- ⭐ **Star Rating** — Interactive 1 to 5 star picker
- 📖 **Chapter Progress** — Track chapters read vs total with animated progress bar
- 🔍 **Search** — Search by title or author
- 🏷 **Filter** — Filter by reading status and book type
- 📊 **Stats Dashboard** — Live count of total / want to read / reading / completed
- ⚡ **Flash Messages** — Auto-dismiss success and error alerts after 4 seconds
- 🔄 **Form Repopulation** — All fields restore after failed validation

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP (procedural — no frameworks) |
| Database | MySQL |
| Frontend | HTML5, CSS3, Vanilla JavaScript |
| Server | XAMPP (Apache + MySQL) |
| Fonts | Playfair Display + DM Sans (Google Fonts) |

---

## Folder Structure

```
mini-library/
│
├── login.php               ← Entry point — login form
├── register.php            ← Create new account
├── index.php               ← Dashboard — view all books
├── add_book.php            ← Add book form
├── edit_book.php           ← Edit book form
├── change_password.php     ← Change password form
├── README.md               ← This file
│
├── actions/
│   ├── add.php             ← Saves new book to DB
│   ├── update.php          ← Updates existing book in DB
│   ├── delete.php          ← Deletes book (POST only)
│   ├── change_password.php ← Updates password in DB
│   └── logout.php          ← Destroys session
│
├── config/
│   └── db.php              ← Database connection
│
├── includes/
│   ├── functions.php       ← Helper functions
│   ├── header.php          ← Navbar + flash messages
│   └── footer.php          ← Closing HTML + JS
│
├── assets/
│   ├── css/style.css       ← All styling
│   └── js/script.js        ← Star picker, link toggle, alerts
│
└── database/
    └── schema.sql          ← SQL to create tables
```

---

## Setup & Installation

### Prerequisites
- XAMPP installed — [download here](https://www.apachefriends.org)
- Apache and MySQL both **running (green)** in XAMPP Control Panel

---

### Step 1 — Copy Project Files

Extract the ZIP and copy the `mini-library` folder to:

```
C:\xampp\htdocs\mini-library\
```

---

### Step 2 — Create the Database

Go to `http://localhost/phpmyadmin` → click the **SQL** tab → paste and run:

```sql
CREATE DATABASE IF NOT EXISTS mini_library;
USE mini_library;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(100) NOT NULL,
    category VARCHAR(100) NOT NULL,
    status ENUM('want_to_read', 'reading', 'completed') DEFAULT 'want_to_read',
    book_type ENUM('online', 'offline') DEFAULT 'offline',
    book_link VARCHAR(500) DEFAULT NULL,
    rating INT DEFAULT NULL CHECK (rating >= 1 AND rating <= 5),
    chapters_total INT DEFAULT NULL,
    chapters_read INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

### Step 3 — Open the App

```
http://localhost/mini-library/login.php
```

Register a new account and you are ready to go. 🎉

---

## Database Schema

### `users` table

| Column | Type | Description |
|---|---|---|
| id | INT PK | Auto increment primary key |
| username | VARCHAR(50) UNIQUE | Login username |
| password | VARCHAR(255) | bcrypt hash — plain text never stored |
| created_at | TIMESTAMP | Account creation time |

### `books` table

| Column | Type | Description |
|---|---|---|
| id | INT PK | Auto increment primary key |
| user_id | INT FK | Links to users.id — cascade on delete |
| title | VARCHAR(200) | Book title — required |
| author | VARCHAR(100) | Author name — required |
| category | VARCHAR(100) | Genre or category — required |
| status | ENUM | want_to_read / reading / completed |
| book_type | ENUM | online / offline |
| book_link | VARCHAR(500) | URL for online books — NULL if physical |
| rating | INT 1–5 | Star rating — NULL if not rated |
| chapters_total | INT | Total chapters — NULL if not set |
| chapters_read | INT | Chapters finished — NULL if not set |
| created_at | TIMESTAMP | When book was added |

> **Note:** `ON DELETE CASCADE` means if a user is deleted, all their books are automatically deleted too.

---

## Helper Functions

All located in `includes/functions.php`

| Function | What It Does |
|---|---|
| `clean($data)` | Strips tags + trims + escapes special chars — prevents XSS |
| `redirect($url)` | Sends Location header + exit() — stops code after redirect |
| `requireLogin()` | Checks `$_SESSION['user_id']` — redirects to login if missing |
| `statusBadge($status)` | Returns colored HTML badge for reading status |
| `starRating($rating)` | Returns ★★★☆☆ HTML or "Not rated" if null |
| `bookTypeLabel($type, $link)` | Returns clickable link or label — validates URL scheme first |

---

## Security

| Threat | Protection |
|---|---|
| SQL Injection | Prepared statements with `?` placeholders on every single query |
| XSS Attack | `clean()` uses `htmlspecialchars` + `strip_tags` on all output |
| Unauthenticated Access | `requireLogin()` at top of every protected page |
| Cross-user Data Access | Every query includes `AND user_id = ?` check |
| Accidental DELETE via URL | Delete only accepts POST requests — GET is rejected |
| Malicious Book Links | URL validated with regex — only `http://` and `https://` allowed |
| Password Theft | `password_hash()` bcrypt — plain text never stored anywhere |

---

## How It Works

```
User visits login.php
        ↓
Submits credentials → password_verify() checks against bcrypt hash
        ↓
Login success → $_SESSION['user_id'] and ['username'] set
        ↓
index.php loads → requireLogin() passes → books queried
        ↓
SQL: WHERE user_id = ? → only their books returned
        ↓
User adds book → add_book.php → POST → actions/add.php
        ↓
Validated → INSERT INTO books → flash success → dashboard
        ↓
User edits → edit_book.php → POST → actions/update.php
        ↓
UPDATE WHERE id = ? AND user_id = ? → cannot edit others' books
        ↓
User logs out → session_unset() + session_destroy() → login page
```

---

## Common Errors

| Error | Cause | Fix |
|---|---|---|
| `Unknown database 'mini_library'` | Database not created | Run the SQL in Step 2 above |
| `Table doesn't exist` | Tables not created | Run CREATE TABLE SQL in phpMyAdmin |
| `Database connection failed` | MySQL not running | Start MySQL in XAMPP Control Panel |
| Blank page / 404 | Apache not running OR wrong folder | Start Apache, check folder name is exactly `mini-library` |
| PHP code showing as text | Apache not running | Start Apache in XAMPP Control Panel |
| Port conflict on 80 | Another app using port 80 | Change Apache to port 8080 in XAMPP config |
| Space in DB name (` mini_library`) | Created with a space | Drop it and recreate as exactly `mini_library` |

---

## Future Enhancements

- [ ] Book cover image upload
- [ ] Favourite / bookmark toggle
- [ ] Notes and personal review per book
- [ ] Sort by rating, title, or date
- [ ] Pagination — 10 books per page
- [ ] Export books to CSV
- [ ] Reading goal tracker
- [ ] Admin panel
- [ ] Public share link for your library

---

## Default Credentials

None — register your own account at:

```
http://localhost/mini-library/register.php
```

---

## License

This project is for educational purposes.

---

*Built with PHP + MySQL — No Frameworks — XAMPP Compatible*
