<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

$userName = $_SESSION['username'];
$firstName = $_SESSION['firstName'];
$course = $_SESSION['course'];
$idNumber = $_SESSION['idNumber'];
$email = $_SESSION['valid'];

// this session is get from the login
$loggedUserId = $_SESSION['id'];

include('../config.php');
include('../helper.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// get all posts from db together and its comments
$sql = "SELECT 
posts.id as postId,
posts.content,
posts.created_at,
posts.image_path,
poster.Id as posterId,
poster.firstName,
poster.lastName,
comments.Id as commentId,
comments.comment,
commenter.Id as commenterId,
commenter.firstName as commenterFname,
commenter.lastName as commenterLname 
FROM posts 
LEFT JOIN comments ON posts.id = comments.postId 
LEFT JOIN users as poster ON poster.Id = posts.userId
LEFT JOIN users as commenter ON commenter.Id = comments.userId
ORDER BY posts.created_at DESC";

$result = $conn->query($sql);

// all post container together its comments if has
$posts = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $post_id = $row['postId'];
        
        if (!isset($posts[$post_id])) {
            $posts[$post_id] = [
                'id' => $row['postId'],
                'fullName' => $row['firstName'].' '.$row['lastName'],
                'datePosted'=>$row['created_at'],
                'postImage'=>$row['image_path'],
                'postContent'=>$row['content'],
                'comments' => []
            ];
        }

        // this is to assign the posts comments
        if (!is_null($row['commentId'])) {
            $posts[$post_id]['comments'][] = [
                'comment' => $row['comment'],
                'commenter' => $row['commenterFname'].' '.$row['commenterLname'],
            ];
        }
    }
}



$conn->close();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknospace</title>
    <link rel="stylesheet" href="Admin_styles.css">
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-regular-straight/css/uicons-regular-straight.css'>
    
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span>
            </div>
            <div class="nav-links">
                <a href="Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i></a>                
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>                
                <a href="../aboutUs.php">Log Out</a>
            </div>
        </div>
    </header>
    <nav class="nav">
        <ul>
            <li><a href="Homepage.html" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="#maintenance" class="icon"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="#lost&found" class="icon"><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
        </ul>
    </nav>
    <main class="main">
        <div class="create-post">
            <div class="post-header">
                <img src="https://static.thenounproject.com/png/3918329-200.png" alt="Profile Image">
                <div class="post-header-info">
                    <h3><?php echo htmlspecialchars($userName); ?></h3>
                </div>
            </div>
            <div class="post-input" id="postInput">
                <p><?php echo "What's on your mind, " . htmlspecialchars($firstName); ?>?</p>
            </div>
        </div>
    
        <!-- Pop-up Create Post -->
        <div id="postModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Create post</h2>
                <div class="post-header">
                    <img src="https://static.thenounproject.com/png/3918329-200.png" alt="Profile Image">
                    <div class="post-header-info">
                        <h3><?php echo htmlspecialchars($userName); ?></h3>
                        <select id="postAudience">
                            <option value="All students">All students</option>
                            <option value="Department">Department</option>
                        </select>
                    </div>
                </div>
                <textarea id="postContent" placeholder="What's on your mind, Your Name?"></textarea>
                <input type="file" id="postImage" accept="image/*">
                <div class="post-options">
                    <p>Add to your post</p>
                    <div class="option-icons">
                        <i class="fi fi-br-picture"></i>
                    </div>
                </div>
                <button class="btn-post" id="submitPost">Post</button>
            </div>
        </div>


        <!-- Post -->
        <div class="posts">

        </div>
    </main>
    <script src="Admin_Homepage.js"></script>
    <script>
        // Function to fetch and display posts
        function loadPosts() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "fetch_posts.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    document.querySelector('.posts').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Load posts when the page is loaded
        window.onload = loadPosts;
    </script>
    <script src="Student_Homepage.js"></script>
</body>
</html>
