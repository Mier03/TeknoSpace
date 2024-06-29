<?php
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "teknospace"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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

        echo "
            <div class='post-container'>
                <div class='post'>
                    <div class='post-header'>
                        <img src='$profile_image' alt='Profile Image'>
                        <div class='post-header-info'>
                            <h3>$username</h3>
                            <p>$created_at</p>
                        </div>
                    </div>
                    <div class='post-content'>
                        <p>$content</p>";
        if ($image_path) {
            echo "<img src='$image_path' alt='Post Image' style='max-width: 100%; height: auto;'>";
        }
        echo "
                    </div>
                    <div class='post-actions'>
                        <a href='#'>Like</a>
                        <a href='#'>Comment</a>
                        <a href='#'>Share</a>
                    </div>
                </div>
            </div>";
    }
} else {
    echo "No posts found.";
}

$conn->close();
?>
