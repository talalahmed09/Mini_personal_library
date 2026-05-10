<?php

session_start();
require_once '../includes/functions.php';
requireLogin();
require_once '../config/db.php';

$userId = $_SESSION['user_id'];
$id     = (int)($_POST['id'] ?? 0);


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

if (!$id) {
    redirect('../index.php');
}


$stmt = mysqli_prepare($conn, "DELETE FROM books WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);

if (mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) > 0) {
    $_SESSION['flash_success'] = '🗑️ Book deleted successfully.';
} else {
    $_SESSION['flash_error'] = 'Book not found or already deleted.';
}

redirect('../index.php');
?>
