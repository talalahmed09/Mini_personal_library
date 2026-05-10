📚
Mini Library Management System
Project Documentation & README
Built with PHP + MySQL · No Frameworks · XAMPP Compatible

  PHP       MySQL       HTML/CSS/JS       XAMPP  
 
1. Project Overview
A personal book tracking web application built with pure PHP and MySQL. Users can register, log in, and manage their own private collection of books with search, filtering, star ratings, chapter progress tracking, and online/offline book support.

Tech Stack
Layer	Technology
Backend	PHP (procedural — no frameworks)
Database	MySQL
Frontend	HTML5, CSS3, Vanilla JavaScript
Server	XAMPP (Apache + MySQL)
Fonts	Playfair Display + DM Sans (Google Fonts)

Features
•	Authentication — Register, Login, Logout, Change Password
•	Book CRUD — Add, Edit, Delete, View all books
•	Reading Status — Want to Read / Reading / Completed
•	Book Type — Online (with clickable link) or Physical
•	Star Rating — 1 to 5 stars, interactive picker
•	Chapter Progress — Track chapters read vs total with progress bar
•	Search — Search by title or author
•	Filter — Filter by reading status and book type
•	Stats Dashboard — Live count of total / want / reading / completed
•	Flash Messages — Auto-dismiss success and error alerts
•	Form Repopulation — Fields restore after failed validation

2. Setup & Installation
Prerequisites
•	XAMPP installed — download from apachefriends.org
•	Apache and MySQL both running (green) in XAMPP Control Panel
•	A browser — Chrome, Firefox, Edge

Step 1 — Copy Project Files
Extract the ZIP and copy the mini-library folder to:
C:\xampp\htdocs\mini-library\
Final path should be: C:\xampp\htdocs\mini-library\index.php

Step 2 — Create the Database
Go to http://localhost/phpmyadmin → click the SQL tab → paste and run:
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
    status ENUM('want_to_read','reading','completed') DEFAULT 'want_to_read',
    book_type ENUM('online','offline') DEFAULT 'offline',
    book_link VARCHAR(500) DEFAULT NULL,
    rating INT DEFAULT NULL CHECK (rating >= 1 AND rating <= 5),
    chapters_total INT DEFAULT NULL,
    chapters_read INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

Step 3 — Open the App
Open your browser and go to:
http://localhost/mini-library/login.php
Register a new account and you are ready to go.

3. Folder Structure
mini-library/
│
├── login.php               ← Entry point — login form
├── register.php            ← Create new account
├── index.php               ← Dashboard — view all books
├── add_book.php            ← Add book form
├── edit_book.php           ← Edit book form
├── change_password.php     ← Change password form
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
│   └── js/script.js        ← Stars, link toggle, alerts
│
└── database/
    └── schema.sql          ← SQL to create tables

4. Database Schema
users table
Column	Type	Description
id	INT PK	Auto increment primary key
username	VARCHAR(50) UNIQUE	Login username — must be unique
password	VARCHAR(255)	bcrypt hash — plain text never stored
created_at	TIMESTAMP	Account creation time (auto)

books table
Column	Type	Description
id	INT PK	Auto increment primary key
user_id	INT FK	Links to users.id — cascade delete
title	VARCHAR(200)	Book title — required
author	VARCHAR(100)	Author name — required
category	VARCHAR(100)	Genre or category — required
status	ENUM	want_to_read / reading / completed
book_type	ENUM	online / offline
book_link	VARCHAR(500)	URL for online books — NULL if physical
rating	INT 1–5	Star rating — NULL if not rated
chapters_total	INT	Total chapters — NULL if not set
chapters_read	INT	Chapters finished — NULL if not set
created_at	TIMESTAMP	When book was added (auto)

5. Security
Threat	Protection
SQL Injection	Prepared statements with ? placeholders on every query
XSS Attack	clean() uses htmlspecialchars + strip_tags on all output
Unauth Access	requireLogin() at top of every protected page
Cross-user Data	Every query includes AND user_id = ? check
Accidental Delete	Delete only accepts POST — GET requests rejected
Malicious Links	URL validated with regex — only http/https allowed
Password Theft	password_hash() bcrypt — plain text never stored

6. Helper Functions
All located in includes/functions.php

Function	What It Does
clean($data)	Strips tags, trims, escapes special chars — prevents XSS
redirect($url)	Sends Location header + exit() — stops code after redirect
requireLogin()	Checks $_SESSION[user_id] — redirects to login if missing
statusBadge($s)	Returns colored HTML badge for reading status
starRating($r)	Returns star HTML — e.g. 3 stars = ★★★☆☆
bookTypeLabel($t,$l)	Returns clickable link or label — validates URL scheme first

7. How It Works
User visits login.php
        ↓
Submits credentials → password_verify() checks hash
        ↓
Session created → $_SESSION[user_id] set
        ↓
index.php → requireLogin() passes → books queried
        ↓
WHERE user_id = ? → only their books shown
        ↓
User adds book → add_book.php → POST → actions/add.php
        ↓
Validated → INSERT INTO books → flash success → dashboard
        ↓
User logs out → session_unset() + session_destroy()

8. Common Errors & Fixes
Error	Fix
Unknown database mini_library	Run the CREATE DATABASE SQL in phpMyAdmin
Table does not exist	Run the CREATE TABLE SQL in phpMyAdmin
Database connection failed	Start MySQL in XAMPP Control Panel
Blank page	Start Apache in XAMPP Control Panel
PHP showing as text	Apache is not running — start it in XAMPP
Port conflict on 80	Change Apache port to 8080 in XAMPP config
Space in DB name	Drop and recreate as exactly: mini_library

9. How to Push to GitHub
Step 1 — Install Git
Download from git-scm.com and install. Open Command Prompt and verify:
git --version

Step 2 — Create GitHub Repository
1.	Go to github.com and sign in
2.	Click the + button → New repository
3.	Name it: mini-library
4.	Leave it Public or Private — your choice
5.	Do NOT check "Add README" — you already have one
6.	Click Create repository

Step 3 — Open Command Prompt in Your Project
Open Command Prompt and navigate to your project:
cd C:\xampp\htdocs\mini-library

Step 4 — Push the Code
Run these commands one by one:
-- Initialize git in this folder
git init
 
-- Add all files
git add .
 
-- Create first commit
git commit -m "Initial commit — Mini Library Management System"
 
-- Connect to your GitHub repo (replace YOUR_USERNAME)
git remote add origin https://github.com/YOUR_USERNAME/mini-library.git
 
-- Push to GitHub
git push -u origin main
GitHub will ask for your username and password (or Personal Access Token).

Step 5 — Add .gitignore (Recommended)
Create a file called .gitignore in your project folder with this content — so you don't push sensitive config:
# Ignore XAMPP error logs
*.log
 
# Ignore OS files
.DS_Store
Thumbs.db

Step 6 — Add README.md
Create a file called README.md in your mini-library/ folder and paste the markdown README content into it. GitHub will render it automatically as a formatted page.

Future Updates — Pushing Changes
Every time you make changes, run:
git add .
git commit -m "Describe what you changed"
git push

10. Future Enhancements
•	Book cover image upload
•	Favourite / bookmark toggle
•	Notes and personal review per book
•	Sort by rating, title, or date added
•	Pagination — 10 books per page
•	Export books to CSV
•	Reading goal tracker — set yearly target
•	Admin panel — view all users and books
•	Public share link — read-only view of your library
•	Password strength meter on register page

Built with PHP + MySQL — No Frameworks — XAMPP Compatible
