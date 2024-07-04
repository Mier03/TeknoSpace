<?php
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = $_POST['postId'];
    
    $sql = "DELETE FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
}