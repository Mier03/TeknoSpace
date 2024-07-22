<?php
include('../config.php');

$json = file_get_contents('php://input');
$userData = json_decode($json, true);

if (!$userData || !isset($userData['Id'], $userData['userType'], $userData['firstName'], $userData['middleName'], $userData['lastName'], $userData['idNumber'], $userData['course'], $userData['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

$sql = "UPDATE users SET userType=?, firstName=?, middleName=?, lastName=?, idNumber=?, course=?, email=? WHERE Id=?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssssssi", $userData['userType'], $userData['firstName'], $userData['middleName'], $userData['lastName'], $userData['idNumber'], $userData['course'], $userData['email'], $userData['Id']);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating data: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Error preparing statement: ' . $conn->error]);
}

$conn->close();