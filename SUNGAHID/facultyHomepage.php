<?php
session_start();
if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}
$userName = $_SESSION['username'];
$fullName = $_SESSION['firstName'].' '.$_SESSION['lastName'];
$course = $_SESSION['course'];
$idNumber = $_SESSION['idNumber'];
$email = $_SESSION['valid'];
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
                    <h3><?php echo $fullName?></h3>
                </div>
            </div>
            <div class="post-input" id="postInput">
                <p>What's on your mind, Your Name?</p>
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
                        <h3><?php echo $fullName?></h3>
                        <select id="postAudience">
                            <option value="All students">All students</option>
                            <option value="Department">Department</option>
                        </select>
                    </div>
                </div>
                <textarea id="postContent" placeholder="What's on your mind, <?php echo $fullName ?>?"></textarea>
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
        <div class="posts">
        </div>
    </main>
   
    <script src="post.js"></script>
    <script src="comment.js"></script>
</body>
</html>
