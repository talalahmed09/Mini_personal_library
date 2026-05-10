<?php

session_start();
require_once '../includes/functions.php';
requireLogin();
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('../index.php');
}

$userId          = $_SESSION['user_id'];
$currentPassword = $_POST['current_password'] ?? '';
$newPassword     = $_POST['new_password']     ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';


if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
    $_SESSION['flash_error'] = 'All fields are required.';
    redirect('../change_password.php');
}

if (strlen($newPassword) < 4) {
    $_SESSION['flash_error'] = 'New password must be at least 4 characters.';
    redirect('../change_password.php');
}


if ($newPassword !== $confirmPassword) {
    $_SESSION['flash_error'] = 'New passwords do not match.';
    redirect('../change_password.php');
}

$stmt = mysqli_prepare($conn, "SELECT password FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user   = mysqli_fetch_assoc($result);

if (!$user) {
    $_SESSION['flash_error'] = 'User not found.';
    redirect('../change_password.php');
}

if (!password_verify($currentPassword, $user['password'])) {
    $_SESSION['flash_error'] = 'Current password is incorrect.';
    redirect('../change_password.php');
}


if (password_verify($newPassword, $user['password'])) {
    $_SESSION['flash_error'] = 'New password must be different from your current password.';
    redirect('../change_password.php');
}


$newHash = password_hash($newPassword, PASSWORD_DEFAULT);
$update  = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE id = ?");
mysqli_stmt_bind_param($update, 'si', $newHash, $userId);

if (mysqli_stmt_execute($update)) {
    $_SESSION['flash_success'] = '🔑 Password changed successfully!';
    redirect('../index.php');
} else {
    $_SESSION['flash_error'] = 'Failed to update password. Please try again.';
    redirect('../change_password.php');
}
?>
