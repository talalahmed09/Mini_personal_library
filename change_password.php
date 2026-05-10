<?php

session_start();
require_once 'includes/functions.php';
requireLogin();

include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Change Password</h1>
        <p class="page-subtitle">Update your account password</p>
    </div>
    <a href="index.php" class="btn btn-ghost">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="actions/change_password.php">

        <div class="form-group" style="margin-bottom: 1.2rem;">
            <label class="form-label">Current Password *</label>
            <input type="password" name="current_password" class="form-input"
                   placeholder="Enter your current password" required>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">New Password *</label>
                <input type="password" name="new_password" class="form-input"
                       placeholder="Enter new password" required>
                <small class="form-hint">Minimum 4 characters.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password *</label>
                <input type="password" name="confirm_password" class="form-input"
                       placeholder="Repeat new password" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Password →</button>
            <a href="index.php" class="btn btn-ghost">Cancel</a>
        </div>

    </form>
</div>

<?php include 'includes/footer.php'; ?>
