<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknospace</title>
    <link rel="stylesheet" href="Student_styles.css">
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
                <a href="#profile" class="icon"><i class="fi fi-ss-user"></i></a>                
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>                
                <a href="../Camus_Welcome/welcome.php">Log Out</a>
            </div>
        </div>
    </header>
    <nav class="nav">
        <ul>
            <li><a href="Student_Homepage.html" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="#maintenance" class="icon"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="#lost&found" class="icon"><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
        </ul>
    </nav>
    <main class="main">
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
        </div>
    </main>
    <script src="Student_Homepage.js"></script>
</body>
</html>
