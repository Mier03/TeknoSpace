<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

include('../config.php');

$userId = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileUpload'])) {
    $uploadType = $_POST['uploadType'];
    $targetDir = "../uploads/";
    $fileName = basename($_FILES["fileUpload"]["name"]);
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    // Allow certain file formats
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
        // Upload file to server
        if (move_uploaded_file($_FILES["fileUpload"]["tmp_name"], $targetFilePath)) {
            // Update database with new file path
            if ($uploadType == 'profile') {
                $update = $conn->query("UPDATE profile SET profile_pic = '$targetFilePath' WHERE userId = '$userId'");
            } elseif ($uploadType == 'cover') {
                $update = $conn->query("UPDATE profile SET cover_photo = '$targetFilePath' WHERE userId = '$userId'");
            }

            if ($update) {
                header("Location: Profile_Page.php");
                exit();
            } else {
                echo "File upload failed, please try again.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.";
    }
}
?>
