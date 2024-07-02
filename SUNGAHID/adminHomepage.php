<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}
include('../config.php');
include('../helper.php');

$userName = $_SESSION['username'];
$firstName = $_SESSION['firstName'];
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
    
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
            <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span>
            </div>
            <div class="nav-links">
                <a href="#home" class="icon"><i class="fi fi-ss-user"></i></a>                
                <a href="#profile" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>                
                <a href="../Camus_Welcome/welcome.php">Log Out</a>
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
                    <h3><?php echo htmlspecialchars($firstName); ?></h3>
                </div>
            </div>
            <div class="post-input" id="postInput">
                <p><?php echo "What's on your mind, " . htmlspecialchars($firstName) . "?"; ?></p>
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
                        <h3><?php echo htmlspecialchars($firstName); ?></h3>
                        <select>
                            <option>All students</option>
                            <option>Department</option>
                        </select>
                    </div>
                </div>
<<<<<<< Updated upstream
                <textarea placeholder="<?php echo "What's on your mind, " . htmlspecialchars($firstName) . "?"; ?>"></textarea>
=======
                <textarea id="postContent" placeholder="What's on your mind, <?php echo htmlspecialchars($firstName); ?>?"></textarea>
                <input type="file" id="postImage" accept="image/*">
>>>>>>> Stashed changes
                <div class="post-options">
                    <p>Add to your post</p>
                    <div class="option-icons">
                        <i class="fi fi-br-picture"></i>
                    </div>
                </div>
                <button class="btn-post">Post</button>
            </div>
        </div>

        <div class="posts">
            <div class="post">
                <div class="post-header">
                    <img src="https://static.thenounproject.com/png/3918329-200.png" alt="Profile Image">
                    <div class="post-header-info">
                        <h3>John Doe</h3>
                        <p>2 hours ago</p>
                    </div>
                </div>
                <div class="post-content">
                    <p>This is an example post content.</p>
                </div>
                <div class="post-actions">
                    <a href="#">Like</a>
                    <a href="#">Comment</a>
                    <a href="#">Share</a>
                </div>
            </div>

        </div>
    </main>
    <script src="Admin_Homepage.js"></script>
<<<<<<< Updated upstream
=======
    <script>
        submitPost.onclick = function() {
        var content = postContent.value;
        var audience = postAudience.value;
        var imageFile = postImage.files[0];

        if (content) {
            var formData = new FormData();
            formData.append("content", content);
            formData.append("audience", audience);
            if (imageFile) {
                formData.append("image", imageFile);
            }

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "create_post.php", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                    loadPosts();
                    postContent.value = '';
                    postImage.value = '';
                    modal.style.display = "none";
                }
            };
            xhr.send(formData);
        }
    }
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
>>>>>>> Stashed changes
</body>
</html>
