<?php
include('../config.php');

$postId = isset($_POST['postId']) ? intval($_POST['postId']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($postId && ($action === 'make' || $action === 'remove')) {
    $isImportant = ($action === 'make') ? 1 : 0;

    $stmt = $conn->prepare("UPDATE posts SET is_important = ?, updated_important_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("ii", $isImportant, $postId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}

$conn->close();
?>