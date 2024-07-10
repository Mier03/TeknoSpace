<?php
header('Content-Type: application/json');

$userId = $_GET['userId'];

include('../config.php');

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

$sql = "SELECT profile_pic, cover_photo FROM profile WHERE userId = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'profile_pic' => $row['profile_pic'],
        'cover_photo' => $row['cover_photo']
    ]);
} else {
    echo json_encode(['error' => 'No image found']);
}

$stmt->close();
$conn->close();
?>