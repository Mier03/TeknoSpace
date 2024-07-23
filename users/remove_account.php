<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['userId'] ?? null;


if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$conn->begin_transaction();

try {
    // Delete from comments table
    $stmt = $conn->prepare("DELETE FROM comments WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Delete from likes table
    $stmt = $conn->prepare("DELETE FROM likes WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Delete from posts table
    $stmt = $conn->prepare("DELETE FROM posts WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Delete from profile table
    $stmt = $conn->prepare("DELETE FROM profile WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Delete from users table
    $stmt = $conn->prepare("DELETE FROM users WHERE Id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
