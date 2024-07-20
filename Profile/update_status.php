<?php
include('../config.php');

$postId = isset($_POST['postId']) ? intval($_POST['postId']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($postId && $action === 'found') {
    $stmt = $conn->prepare("UPDATE posts SET status = 'found', updated_status_at = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $postId);

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
