<?php

session_start();
require_once 'includes/functions.php';
requireLogin();

$old = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_data']);

function old($key, $default = '') {
    global $old;
    return isset($old[$key]) ? htmlspecialchars($old[$key]) : $default;
}

include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Add New Book</h1>
        <p class="page-subtitle">Add a book to your library</p>
    </div>
    <a href="index.php" class="btn btn-ghost">← Back</a>
</div>

<div class="form-card">
    <form method="POST" action="actions/add.php" id="bookForm">

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Book Title *</label>
                <input type="text" name="title" class="form-input"
                       placeholder="e.g. The Great Gatsby" required
                       value="<?= old('title') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Author *</label>
                <input type="text" name="author" class="form-input"
                       placeholder="e.g. F. Scott Fitzgerald" required
                       value="<?= old('author') ?>">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Category *</label>
                <input type="text" name="category" class="form-input"
                       placeholder="e.g. Fiction, Self-Help, Science..." required
                       value="<?= old('category') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Reading Status *</label>
                <select name="status" class="form-input form-select" required>
                    <option value="want_to_read" <?= old('status') === 'want_to_read' ? 'selected' : '' ?>>📌 Want to Read</option>
                    <option value="reading"      <?= old('status') === 'reading'      ? 'selected' : '' ?>>📖 Currently Reading</option>
                    <option value="completed"    <?= old('status') === 'completed'    ? 'selected' : '' ?>>✅ Completed</option>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Book Type *</label>
                <select name="book_type" class="form-input form-select" id="bookTypeSelect" required>
                    <option value="offline" <?= old('book_type', 'offline') === 'offline' ? 'selected' : '' ?>>📖 Physical Book</option>
                    <option value="online"  <?= old('book_type', 'offline') === 'online'  ? 'selected' : '' ?>>🌐 Online Book</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Rating (optional)</label>
                <div class="star-picker" id="starPicker">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star-option <?= (old('rating') && $i <= (int)old('rating')) ? 'active' : '' ?>"
                              data-val="<?= $i ?>">★</span>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="<?= old('rating') ?>">
            </div>
        </div>

      
        <div class="form-group" id="linkGroup"
             style="display: <?= old('book_type') === 'online' ? 'block' : 'none' ?>">
            <label class="form-label">Book Link *</label>
            <input type="url" name="book_link" id="bookLinkInput" class="form-input"
                   placeholder="https://example.com/read-book"
                   value="<?= old('book_link') ?>">
            <small class="form-hint">Paste the URL where this book can be read online.</small>
        </div>

      
        <div class="form-row" id="chapterGroup"
             style="display: <?= old('status') === 'reading' ? 'grid' : 'none' ?>">
            <div class="form-group">
                <label class="form-label">Total Chapters</label>
                <input type="number" name="chapters_total" class="form-input"
                       placeholder="e.g. 24" min="1"
                       value="<?= old('chapters_total') ?>">
                <small class="form-hint">How many chapters does this book have?</small>
            </div>
            <div class="form-group">
                <label class="form-label">Chapters Read</label>
                <input type="number" name="chapters_read" class="form-input"
                       placeholder="e.g. 10" min="0"
                       value="<?= old('chapters_read') ?>">
                <small class="form-hint">How many have you finished so far?</small>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add to Library →</button>
            <a href="index.php" class="btn btn-ghost">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
