<?php
$servername = "127.0.0.1";
$username = "root"; 
$password = ""; 
$dbname = "teknospace"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

<<<<<<< Updated upstream
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
=======
// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
include('../config.php');
include('../helper.php');// Check connection

>>>>>>> Stashed changes

$sql = "SELECT * FROM posts ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $post_id = $row['id'];
        $username = $row['username'];
        $content = $row['content'];
        $audience = $row['audience'];
        $profile_image = $row['profile_image'];
        $image_path = $row['image_path'];
        $created_at = $row['created_at'];

        echo '
            <div class="post-container">
                <div class="post">
                    <div class="post-header">
                        <img src="'.$profile_image.'" alt="Profile Image">
                        <div class="post-header-info">
                            <h3>'.$username.'</h3>
                            <p>'.$created_at.'</p>
                        </div>
                    </div>
                    <div class="post-content">
                        <p>'.$content.'</p>';
        if ($image_path) {
            echo '<img src="'.$image_path.'" alt="Post Image" style="max-width: 100%; height: auto;">';
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
    }
} else {
    echo "No posts found.";
}

$conn->close();
?>
