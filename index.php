<?php

session_start();
require_once 'includes/functions.php';
requireLogin();
require_once 'config/db.php';

$userId = $_SESSION['user_id'];


$search   = clean($_GET['search'] ?? '');
$filter   = clean($_GET['filter'] ?? '');
$typeFilter = clean($_GET['type'] ?? '');


$where = ["b.user_id = ?"];
$params = [$userId];
$types  = 'i';

if (!empty($search)) {
    $where[] = "(b.title LIKE ? OR b.author LIKE ?)";
    $like = "%$search%";
    $params[] = $like;
    $params[] = $like;
    $types .= 'ss';
}
if (!empty($filter) && in_array($filter, ['want_to_read', 'reading', 'completed'])) {
    $where[] = "b.status = ?";
    $params[] = $filter;
    $types .= 's';
}
if (!empty($typeFilter) && in_array($typeFilter, ['online', 'offline'])) {
    $where[] = "b.book_type = ?";
    $params[] = $typeFilter;
    $types .= 's';
}

$whereStr = implode(' AND ', $where);
$sql = "SELECT * FROM books b WHERE $whereStr ORDER BY b.created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$books  = mysqli_fetch_all($result, MYSQLI_ASSOC);

$statsQuery = mysqli_prepare($conn,
    "SELECT
        COUNT(*) AS total,
        COALESCE(SUM(status='want_to_read'), 0) AS want,
        COALESCE(SUM(status='reading'), 0) AS reading,
        COALESCE(SUM(status='completed'), 0) AS completed
     FROM books WHERE user_id = ?");
mysqli_stmt_bind_param($statsQuery, 'i', $userId);
mysqli_stmt_execute($statsQuery);
$stats = mysqli_fetch_assoc(mysqli_stmt_get_result($statsQuery));

include 'includes/header.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">My Library</h1>
        <p class="page-subtitle">Your personal reading collection</p>
    </div>
    <a href="add_book.php" class="btn btn-primary">+ Add Book</a>
</div>

<!-- Stats Row -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-number"><?= $stats['total'] ?></div>
        <div class="stat-label">Total Books</div>
    </div>
    <div class="stat-card stat-want">
        <div class="stat-number"><?= $stats['want'] ?></div>
        <div class="stat-label">Want to Read</div>
    </div>
    <div class="stat-card stat-reading">
        <div class="stat-number"><?= $stats['reading'] ?></div>
        <div class="stat-label">Reading</div>
    </div>
    <div class="stat-card stat-done">
        <div class="stat-number"><?= $stats['completed'] ?></div>
        <div class="stat-label">Completed</div>
    </div>
</div>

<form method="GET" action="" class="search-bar">
    <input type="text" name="search" class="search-input"
           placeholder="🔍 Search by title or author..."
           value="<?= htmlspecialchars($search) ?>">

    <select name="filter" class="filter-select">
        <option value="">All Statuses</option>
        <option value="want_to_read" <?= $filter === 'want_to_read' ? 'selected' : '' ?>>Want to Read</option>
        <option value="reading"      <?= $filter === 'reading'      ? 'selected' : '' ?>>Reading</option>
        <option value="completed"    <?= $filter === 'completed'    ? 'selected' : '' ?>>Completed</option>
    </select>

    <select name="type" class="filter-select">
        <option value="">All Types</option>
        <option value="online"  <?= $typeFilter === 'online'  ? 'selected' : '' ?>>Online</option>
        <option value="offline" <?= $typeFilter === 'offline' ? 'selected' : '' ?>>Physical</option>
    </select>

    <button type="submit" class="btn btn-secondary">Search</button>
    <?php if ($search || $filter || $typeFilter): ?>
        <a href="index.php" class="btn btn-ghost">Clear</a>
    <?php endif; ?>
</form>

<?php if (empty($books)): ?>
    <div class="empty-state">
        <div class="empty-icon">📭</div>
        <h3>No books found</h3>
        <p><?= ($search || $filter || $typeFilter) ? 'Try adjusting your search.' : 'Start by adding your first book!' ?></p>
        <?php if (!$search && !$filter && !$typeFilter): ?>
            <a href="add_book.php" class="btn btn-primary">+ Add Your First Book</a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="book-grid">
        <?php foreach ($books as $book): ?>
        <div class="book-card">
            <div class="book-card-header">
                <div class="book-type-tag"><?= bookTypeLabel($book['book_type']) ?></div>
                <?= statusBadge($book['status']) ?>
            </div>

            <h3 class="book-title"><?= clean($book['title']) ?></h3>
            <p class="book-author">by <?= clean($book['author']) ?></p>
            <p class="book-category">📁 <?= clean($book['category']) ?></p>

            <div class="book-rating"><?= starRating($book['rating']) ?></div>

            <?php if ($book['book_type'] === 'online' && $book['book_link']): ?>
                <div class="book-link-row">
                    <?= bookTypeLabel($book['book_type'], $book['book_link']) ?>
                </div>
            <?php endif; ?>

            <?php if ($book['status'] === 'reading' && !empty($book['chapters_total']) && (int)$book['chapters_total'] > 0):
                $chapRead  = (int)$book['chapters_read'];
                $chapTotal = (int)$book['chapters_total'];
                $pct       = min(100, round($chapRead / $chapTotal * 100));
            ?>
                <div class="chapter-progress">
                    <div class="chapter-progress-label">
                        <span>📖 Ch. <?= $chapRead ?> / <?= $chapTotal ?></span>
                        <span><?= $pct ?>%</span>
                    </div>
                    <div class="chapter-progress-bar">
                        <div class="chapter-progress-fill" style="width: <?= $pct ?>%"></div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="book-actions">
                <a href="edit_book.php?id=<?= $book['id'] ?>" class="btn btn-sm btn-edit">✏️ Edit</a>
                <form method="POST" action="actions/delete.php" style="display:inline;"
                      onsubmit="return confirm('Delete \'<?= clean($book['title']) ?>\'?')">
                    <input type="hidden" name="id" value="<?= $book['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-delete">🗑️ Delete</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
