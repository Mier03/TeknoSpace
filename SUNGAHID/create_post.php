<?php
session_start();

// this session is get from the login
$loggedUserId = $_SESSION['id'];

// $servername = "127.0.0.1";
// $username = "root"; 
// $password = ""; 
// $dbname = "teknospace"; 

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
$conn = mysqli_connect("localhost","root","","accounts") or die("Couldn't connect");

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

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
        $imagePath = $targetFile;
    }

    $sql = "INSERT INTO posts (username, content, audience, profile_image, image_path, userId) VALUES ('$username', '$content', '$audience', '$profile_image', '$imagePath', '$loggedUserId')";

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