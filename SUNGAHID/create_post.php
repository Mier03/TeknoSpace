<?php
$servername = "127.0.0.1";
$username = "root"; 
$password = ""; 
$dbname = "teknospace"; 

<<<<<<< Updated upstream
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
=======
// this session is get from the login
$loggedUserId = $_SESSION['id'];
$username = $_SESSION['username'];

// $servername = "127.0.0.1";
// $username = "root"; 
// $password = ""; 
// $dbname = "teknospace"; 

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
include('../config.php');
include('../helper.php');
>>>>>>> Stashed changes

/*$userQuery = "SELECT username FROM users WHERE id = $loggedUserId";
$userResult = $conn->query($userQuery);

if ($userResult->num_rows > 0) {
    // Fetch the username
    $userRow = $userResult->fetch_assoc();
    $username = htmlspecialchars($userRow['username']);
} else {
    die("User not found.");
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['content'];
    $audience = $_POST['audience'];
    
    $profile_image = "https://static.thenounproject.com/png/3918329-200.png"; // Replace with dynamic profile image if applicable
    $imagePath = "";

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = $targetFile;
    }

    $sql = "INSERT INTO posts (username, content, audience, profile_image, image_path) VALUES ('$username', '$content', '$audience', '$profile_image', '$imagePath')";

    if ($conn->query($sql) === TRUE) {
        $post_id = $conn->insert_id;
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
        echo '
                    </div>
                    <div class="post-actions">
                        <a href="#" class="like-btn"><i class="fi fi-rs-social-network"></i> Like</a>
                        <a href="#" class="comment-btn"><i class="fi fi-ts-comment-dots"></i> Comment</a>
                    </div>
                    <div class="comments-section" style="display: none;">
                        <div class="comment-input">
                            <input type="text" placeholder="Write a comment...">
                            <button class="submit-comment"><i class="fi fi-ss-paper-plane-top"></i></button>
                        </div>
                        <div class="comments-list"></div>
                    </div>
                </div>
            </div>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>