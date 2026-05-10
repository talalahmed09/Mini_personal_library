<?php
// ============================================
// Header Include
// ============================================
// Guard: start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Mini Library</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/mini-library/assets/css/style.css">
</head>
<body>

<nav class="navbar">
    <a href="/mini-library/index.php" class="nav-brand">📚 Mini Library</a>
    <?php if (isset($_SESSION['user_id'])): ?>
    <div class="nav-links">
        <span class="nav-user">👤 <?= clean($_SESSION['username']) ?></span>
        <a href="/mini-library/add_book.php" class="nav-link <?= $currentPage === 'add_book.php' ? 'active' : '' ?>">+ Add Book</a>
        <a href="/mini-library/change_password.php" class="nav-link <?= $currentPage === 'change_password.php' ? 'active' : '' ?>">🔑 Password</a>
        <a href="/mini-library/actions/logout.php" class="nav-link nav-logout">Logout</a>
    </div>
    <?php endif; ?>
</nav>

<div class="container">
<?php if (isset($_SESSION['flash_success'])): ?>
    <div class="alert alert-success"><?= $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert alert-error"><?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>
