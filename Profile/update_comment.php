<?php
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'];
    $new_text = $_POST['new_text'];

    $query = "UPDATE comments SET comment = ?, updated_comment_at = CURRENT_TIMESTAMP WHERE Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $new_text, $comment_id);
    $success = $stmt->execute();

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update comment']);
    }

    $stmt->close();
    $conn->close();
}
