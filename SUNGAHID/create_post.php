<?php
session_start();

include('../php/config.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];
    $audience = $_POST['audience'];
    $username = "Your Name"; // Replace with dynamic user name if applicable
    $profile_image = "https://static.thenounproject.com/png/3918329-200.png"; // Replace with dynamic profile image if applicable
    $imagePath = "";
    $loggedInUserId = $_SESSION['id'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = $targetFile;
    }

    $sql = "INSERT INTO posts (username, content, audience, profile_image, image_path, userId) VALUES ('$username', '$content', '$audience', '$profile_image', '$imagePath', '$loggedInUserId')";

    if ($conn->query($sql) === TRUE) {
        echo "
            <div class='post'>
                <div class='post-header'>
                    <img src='$profile_image' alt='Profile Image'>
                    <div class='post-header-info'>
                        <h3>$username</h3>
                        <p>Just now</p>
                    </div>
                </div>
                <div class='post-content'>
                    <p>$content</p>";
        if ($imagePath) {
            echo "<img src='$imagePath' alt='Post Image' style='max-width: 100%; height: auto;'>";
        }
        echo "
                </div>
                <div class='post-actions'>
                    <a href='#'>Like</a>
                    <a href='#'>Comment</a>
                    <a href='#'>Share</a>
                </div>
            </div>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
