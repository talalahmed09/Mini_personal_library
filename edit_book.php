<?php

session_start();
require_once 'includes/functions.php';
requireLogin();
require_once 'config/db.php';

$userId = $_SESSION['user_id'];
$id     = (int)($_GET['id'] ?? 0);

if (!$id) {
    redirect('index.php');
}


$stmt = mysqli_prepare($conn, "SELECT * FROM books WHERE id = ? AND user_id = ?");
mysqli_stmt_bind_param($stmt, 'ii', $id, $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$book   = mysqli_fetch_assoc($result);

if (!$book) {
    $_SESSION['flash_error'] = 'Book not found.';
    redirect('index.php');
}

include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Book</h1>
        <p class="page-subtitle">Update book details</p>
    </div>
    <a href="index.php" class="btn btn-ghost">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="actions/update.php" id="bookForm">
        <input type="hidden" name="id" value="<?= $book['id'] ?>">

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Book Title *</label>
                <input type="text" name="title" class="form-input"
                       value="<?= clean($book['title']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Author *</label>
                <input type="text" name="author" class="form-input"
                       value="<?= clean($book['author']) ?>" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Category *</label>
                <input type="text" name="category" class="form-input"
                       value="<?= clean($book['category']) ?>" required>
            </div>
            <div class="form-group">
                <label class="form-label">Reading Status *</label>
                <select name="status" class="form-input form-select" required>
                    <option value="want_to_read" <?= $book['status'] === 'want_to_read' ? 'selected' : '' ?>>📌 Want to Read</option>
                    <option value="reading"      <?= $book['status'] === 'reading'      ? 'selected' : '' ?>>📖 Currently Reading</option>
                    <option value="completed"    <?= $book['status'] === 'completed'    ? 'selected' : '' ?>>✅ Completed</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Book Type *</label>
                <select name="book_type" class="form-input form-select" id="bookTypeSelect" required>
                    <option value="offline" <?= $book['book_type'] === 'offline' ? 'selected' : '' ?>>📖 Physical Book</option>
                    <option value="online"  <?= $book['book_type'] === 'online'  ? 'selected' : '' ?>>🌐 Online Book</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Rating (optional)</label>
                <div class="star-picker" id="starPicker">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star-option <?= ($book['rating'] && $i <= $book['rating']) ? 'active' : '' ?>"
                              data-val="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="<?= $book['rating'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group" id="linkGroup"
             style="display: <?= $book['book_type'] === 'online' ? 'block' : 'none' ?>">
            <label class="form-label">Book Link</label>
            <input type="url" name="book_link" id="bookLinkInput" class="form-input"
                   value="<?= clean($book['book_link'] ?? '') ?>"
                   placeholder="https://example.com/read-book">
            <small class="form-hint">Paste the URL where this book can be read online.</small>
        </div>

        <div class="form-row" id="chapterGroup"
             style="display: <?= $book['status'] === 'reading' ? 'grid' : 'none' ?>">
            <div class="form-group">
                <label class="form-label">Total Chapters</label>
                <input type="number" name="chapters_total" class="form-input"
                       placeholder="e.g. 24" min="1"
                       value="<?= (int)($book['chapters_total'] ?? 0) ?: '' ?>">
                <small class="form-hint">How many chapters does this book have?</small>
            </div>
            <div class="form-group">
                <label class="form-label">Chapters Read</label>
                <input type="number" name="chapters_read" class="form-input"
                       placeholder="e.g. 10" min="0"
                       value="<?= (int)($book['chapters_read'] ?? 0) ?: '' ?>">
                <small class="form-hint">How many have you finished so far?</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Changes →</button>
            <a href="index.php" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
