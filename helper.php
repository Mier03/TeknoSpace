<?php 
function relative_time($created_at) {
    date_default_timezone_set('Asia/Manila');
    
    $now = new DateTime();
    $created = new DateTime($created_at);
    $interval = $now->diff($created);

    if ($interval->y > 0 || $interval->m > 0 || $interval->d > 20) {
        // If more than 20 days ago, just return the date
        return $created->format('F j, Y');
    } elseif ($interval->d > 0) {
        return $interval->d . ' days ago';
    } elseif ($interval->h > 0) {
        return $interval->h . ' hours ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minutes ago';
    } else {
        return 'just now';
    }
}