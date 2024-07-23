<?php
include('../config.php');


$json = file_get_contents('php://input');
$userData = json_decode($json, true);


if (!$userData || !isset($userData['userType'], $userData['firstName'], $userData['middleName'], $userData['lastName'], $userData['idNumber'], $userData['course'], $userData['email'], $userData['password'])) {
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

    $insertSql = "INSERT INTO users (userType, firstName, middleName, lastName, idNumber, course, email, password) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $insertStmt = $conn->prepare($insertSql);

    if ($insertStmt) {

        $insertStmt->bind_param(
            "ssssssss",
            $userData['userType'],
            $userData['firstName'],
            $userData['middleName'],
            $userData['lastName'],
            $userData['idNumber'],
            $userData['course'],
            $userData['email'],
            $userData['password']
        );

        if (!$insertStmt->execute()) {
            throw new Exception('Error inserting data: ' . $insertStmt->error);
        }

        $last_id = $conn->insert_id; // Get the last inserted ID for the profile
        $insertStmt->close();

        $defaultProfilePic = "https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=";
        $defaultCoverPhoto = "https://www.rappler.com/tachyon/2021/09/cit-campus-20210916.png?resize=850%2C315&zoom=1";
        $profileSql = "INSERT INTO profile (userId, profile_pic, cover_photo) 
                           VALUES (?, ?, ?)";
        $profileStmt = $conn->prepare($profileSql);

        if ($profileStmt) {
            $profileStmt->bind_param("iss", $last_id, $defaultProfilePic, $defaultCoverPhoto);

            if (!$profileStmt->execute()) {
                throw new Exception('Error inserting profile data: ' . $profileStmt->error);
            }

            $profileStmt->close();
        } else {
            throw new Exception('Profile statement preparation failed: ' . $conn->error);
        }



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
