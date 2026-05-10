<?php

session_start();
require_once '../includes/functions.php';
requireLogin();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$userId   = $_SESSION['user_id'];
$title    = clean($_POST['title']     ?? '');
$author   = clean($_POST['author']    ?? '');
$category = clean($_POST['category']  ?? '');
$status   = clean($_POST['status']    ?? 'want_to_read');
$bookType = clean($_POST['book_type'] ?? 'offline');
$bookLink = clean($_POST['book_link'] ?? '');
$rating        = !empty($_POST['rating']) ? (int)$_POST['rating'] : null;
$chaptersTotal = isset($_POST['chapters_total']) && $_POST['chapters_total'] !== '' ? (int)$_POST['chapters_total'] : null;
$chaptersRead  = isset($_POST['chapters_read'])  && $_POST['chapters_read']  !== '' ? (int)$_POST['chapters_read']  : null;

if (empty($title) || empty($author) || empty($category)) {
    $_SESSION['flash_error'] = 'Title, Author, and Category are required.';
    $_SESSION['form_data']   = $_POST;
    redirect('../add_book.php');
}

if (!in_array($status, ['want_to_read', 'reading', 'completed'])) {
    $status = 'want_to_read';
}


if (!in_array($bookType, ['online', 'offline'])) {
    $bookType = 'offline';
}

if ($bookType === 'online' && empty($bookLink)) {
    $_SESSION['flash_error'] = 'Online books must have a link.';
    $_SESSION['form_data']   = $_POST;
    redirect('../add_book.php');
}

if ($bookType === 'offline') {
    $bookLink = null;
}

if ($rating !== null && ($rating < 1 || $rating > 5)) {
    $rating = null;
}

if ($status !== 'reading') {
    $chaptersTotal = null;
    $chaptersRead  = null;
}

if ($chaptersTotal !== null && $chaptersRead !== null && $chaptersRead > $chaptersTotal) {
    $chaptersRead = $chaptersTotal;
}

$stmt = mysqli_prepare($conn,
    "INSERT INTO books (user_id, title, author, category, status, book_type, book_link, rating, chapters_read, chapters_total)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

mysqli_stmt_bind_param($stmt, 'isssssssss',
    $userId, $title, $author, $category, $status, $bookType, $bookLink, $rating, $chaptersRead, $chaptersTotal);

if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['form_data']);
    $_SESSION['flash_success'] = "📚 \"$title\" added to your library!";
    redirect('../index.php');
} else {
    $_SESSION['flash_error'] = 'Failed to add book. Please try again.';
    $_SESSION['form_data']   = $_POST;
    redirect('../add_book.php');
}
?>
