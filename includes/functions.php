<?php
// ============================================
// Helper Functions
// ============================================

// Sanitize input to prevent XSS
function clean($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Redirect to a page
function redirect($url) {
    header("Location: $url");
    exit();
}

// Check if user is logged in, redirect if not
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect('/mini-library/login.php');
    }
}

// Get status badge HTML
function statusBadge($status) {
    $map = [
        'want_to_read' => ['label' => 'Want to Read', 'class' => 'badge-want'],
        'reading'      => ['label' => 'Reading',       'class' => 'badge-reading'],
        'completed'    => ['label' => 'Completed',     'class' => 'badge-done'],
    ];
    $s = $map[$status] ?? ['label' => $status, 'class' => 'badge-want'];
    return "<span class='badge {$s['class']}'>{$s['label']}</span>";
}


function starRating($rating) {
    if (!$rating) return "<span class='no-rating'>Not rated</span>";
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return "<span class='stars'>$stars</span>";
}


function bookTypeLabel($type, $link = '') {
    if ($type === 'online') {
        $isValidUrl = !empty($link) && preg_match('#^https?://#i', $link);
        if ($isValidUrl) {
            $safeLink = htmlspecialchars($link, ENT_QUOTES, 'UTF-8');
            return "<a href='$safeLink' target='_blank' rel='noopener noreferrer' class='read-link'>🌐 Read Online</a>";
        }
        return "<span class='type-online'>🌐 Online</span>";
    }
    return "<span class='type-offline'>📖 Physical</span>";
}
?>
