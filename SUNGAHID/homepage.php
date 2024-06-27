<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
    exit();
}

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
    <title>TeknoSpace</title>
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel="stylesheet" href="homepage-styles.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <img src="images/teknospace-logo.jpg" alt="TeknoSpace" class="logo-image">
                TeknoSpace</div>
            <div class="nav-links">
                <a href="#home" class="icon"><i class='bx bxs-home'></i></a>                
                <a href="#profile" class="icon"><i class='bx bx-face'></i></a>                
                <a href="#logout">Log Out</a>
            </div>
        </nav>
    </header>
    <!--sidebar-->
    <div class="container">
        <aside class="sidebar">
        
            <ul class="sidebar-menu">
                <li><a href="#updates" class="menu-item"><i class='bx bx-news'></i> School Updates</a></li>
                <li><a href="#maintenance" class="menu-item"><i class='bx bx-wrench'></i> Maintenance</a></li>
                <li><a href="#lost&found" class="menu-item"><i class='bx bx-search-alt'></i> Lost and Found</a></li>
            </ul>
        </aside>
        <main class="main-content">
            <div class="create-post">
                <div class="post-header">
                    <img src="https://static.thenounproject.com/png/3918329-200.png" alt="Profile Image">
                    <div class="post-header-info">
                        <h3> <?php echo $userName; ?></h3>
                        <p>Public</p>
                    </div>
                </div>
                <textarea rows="4" cols="50" placeholder="What's on your mind, <?php echo htmlspecialchars($firstName); ?>?"></textarea>
                <div class="post-actions">
                    <button class="btn-post">Post</button>
                    <button class="btn-cancel">Cancel</button>
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
    </div>
</body>
</html>