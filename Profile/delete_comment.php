<?php
session_start();
include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment_id = $_POST['comment_id'];

    $query = "DELETE FROM comments WHERE Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $comment_id);
    $success = $stmt->execute();

    if ($success) {
        echo "success";
    } else {
        echo "error:Failed to delete comment";
    }

    $stmt->close();
    $conn->close();
}
