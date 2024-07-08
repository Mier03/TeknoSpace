<?php
include('../config.php');


$json = file_get_contents('php://input');
$userData = json_decode($json, true);


if (!$userData || !isset($userData['userType'], $userData['firstName'], $userData['middleName'], $userData['lastName'], $userData['idNumber'], $userData['course'], $userData['email'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid data received']);
    exit;
}


$conn->begin_transaction();

try {
    // checking for duplicates
    $checkSql = "SELECT COUNT(*) as count FROM users WHERE idNumber = ? OR email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $userData['idNumber'], $userData['email']);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        throw new Exception('User with this ID Number or Email already exists in the users table.');
    }
    
    $checkStmt->close();

    $insertSql = "INSERT INTO users (userType, firstName, middleName, lastName, idNumber, course, email) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);

    if ($insertStmt) {
        
        $insertStmt->bind_param("sssssss", $userData['userType'], $userData['firstName'], $userData['middleName'], $userData['lastName'], $userData['idNumber'], $userData['course'], $userData['email']);

        
        if (!$insertStmt->execute()) {
            throw new Exception('Error inserting data: ' . $insertStmt->error);
        }

        $insertStmt->close();

        
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
        
    } else {

        throw new Exception('Error preparing insert statement: ' . $conn->error);

    }
} catch (Exception $e) {
    
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();