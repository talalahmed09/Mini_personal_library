<?php

session_start();
require_once '../includes/functions.php';
requireLogin();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$userId   = $_SESSION['user_id'];
$id       = (int)($_POST['id'] ?? 0);
$title    = clean($_POST['title']     ?? '');
$author   = clean($_POST['author']    ?? '');
$category = clean($_POST['category']  ?? '');
$status   = clean($_POST['status']    ?? 'want_to_read');
$bookType = clean($_POST['book_type'] ?? 'offline');
$bookLink = clean($_POST['book_link'] ?? '');
$rating        = !empty($_POST['rating']) ? (int)$_POST['rating'] : null;
$chaptersTotal = isset($_POST['chapters_total']) && $_POST['chapters_total'] !== '' ? (int)$_POST['chapters_total'] : null;
$chaptersRead  = isset($_POST['chapters_read'])  && $_POST['chapters_read']  !== '' ? (int)$_POST['chapters_read']  : null;


if (!$id || empty($title) || empty($author) || empty($category)) {
    $_SESSION['flash_error'] = 'All required fields must be filled.';
    redirect("../edit_book.php?id=$id");
}

if (!in_array($status, ['want_to_read', 'reading', 'completed'])) {
    $status = 'want_to_read';
}
if (!in_array($bookType, ['online', 'offline'])) {
    $bookType = 'offline';
}
if ($bookType === 'online' && empty($bookLink)) {
    $_SESSION['flash_error'] = 'Online books must have a link.';
    redirect("../edit_book.php?id=$id");
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
    "UPDATE books SET title=?, author=?, category=?, status=?, book_type=?, book_link=?, rating=?, chapters_read=?, chapters_total=?
     WHERE id=? AND user_id=?");

mysqli_stmt_bind_param($stmt, 'sssssssssii',
    $title, $author, $category, $status, $bookType, $bookLink, $rating, $chaptersRead, $chaptersTotal, $id, $userId);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['flash_success'] = "✅ \"$title\" updated successfully!";
} else {
    $_SESSION['flash_error'] = 'Update failed. Please try again.';
}

redirect('../index.php');
?>
