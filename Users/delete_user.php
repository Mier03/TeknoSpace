<?php

include('../config.php');

$json = file_get_contents('php://input');
$userData = json_decode($json, true);


if (!$userData || !isset($userData['Id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}

try {
    
    $deleteSql = "DELETE FROM verify WHERE Id = ?";
        $deleteStmt = $conn->prepare($deleteSql);

        if ($deleteStmt) {
            $deleteStmt->bind_param("i", $userData['Id']);

            
            if (!$deleteStmt->execute()) {
                throw new Exception('Error deleting data: ' . $deleteStmt->error);
            }

            $deleteStmt->close();
        } else {
            throw new Exception('Error preparing delete statement: ' . $conn->error);
        }

        
        $conn->commit();
        echo json_encode(['success' => true]);

    
} catch (Exception $e) {

    echo json_encode(['success' => false, 'message' => $e->getMessage()]);

}

$conn->close();