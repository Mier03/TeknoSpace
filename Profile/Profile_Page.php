<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

include('../config.php');
include('../helper.php');

$userId = $_SESSION['id'];
$userType = $_SESSION['userType'];
$firstName = $_SESSION['firstName'];
$lastName = $_SESSION['lastName'];
$course = $_SESSION['course'];
$idNumber = $_SESSION['idNumber'];
$email = $_SESSION['valid'];

// Fetch profile data
$sql = "SELECT * FROM profile WHERE userId = '$userId'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $profile = $result->fetch_assoc();
    $profilePic = $profile['profile_pic'];
    $coverPhoto = $profile['cover_photo'];
} else {
    // Default images if no profile found
    $profilePic = "https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=";
    $coverPhoto = "https://cit.edu/wp-content/uploads/2024/03/PNG_2nd-Semester_Website-Banner-2024.png";
    // Insert default images into the profile table
    $insert = $conn->query("INSERT INTO profile (userId, profile_pic, cover_photo) VALUES ('$userId', '$profilePic', '$coverPhoto')");
    if (!$insert) {
        die("Error inserting new profile: " . $conn->error);
    }
}

// Fetch user posts only if user is Faculty
$posts = [];
if ($userType === 'Faculty') {
    $sqlPosts = "SELECT * FROM posts WHERE userId = '$userId' ORDER BY created_at DESC";
    $resultPosts = $conn->query($sqlPosts);
    if ($resultPosts->num_rows > 0) {
        while ($row = $resultPosts->fetch_assoc()) {
            $posts[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="Profile_styles.css">
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-rounded/css/uicons-solid-rounded.css'>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <?php
                if ($userType === 'Student') {
                    echo '<a href="../Users/studentHomepage.php">';
                } elseif ($userType === 'Faculty') {
                    echo '<a href="../Users/facultyHomepage.php">';
                } 
                ?>
                <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span></a>
            </div>
            <div class="nav-links">
                <a href="Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i></a>                
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>                
                <a href="../aboutUs.php">Log Out</a>
            </div>
        </div>
    </header>
    <main>
        <section id="profile">
            <div class="cover-photo">
                <img src="<?php echo $coverPhoto; ?>" alt="Cover Photo">
                <button class="change-photo" id="change-cover"><i class='bx bxs-camera'></i></i></button>
            </div>
            <div class="profile-info">
                <div class="profile-photo">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                    <button class="change-photo" id="change-profile"><i class='bx bxs-camera'></i></i></button>
                </div>
                <div class="user-details">
                    <h1><?php echo $firstName . ' ' . $lastName; ?></h1>
                    <p class="user-role"><?php echo $userType; ?></p>
                </div>
            </div>
            <div class="additional-details">
                <h2>Details</h2>
                <p><strong>Email:</strong> <?php echo $email; ?></p>
                <p><strong>ID Number:</strong> <?php echo $idNumber; ?></p>
            </div>
        </section>

        <?php if ($userType === 'Faculty'): ?>
            <section id="posts">
                <h2>Posts</h2>
                <div id="post-list">
                    <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <div class="post-header">
                            <div class="profile-pic">
                                <img src="<?php echo $profilePic; ?>" alt="Profile Photo"></div>
                            <div class="post-header-info">
                                <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                                <span class="post-date"><?php echo htmlspecialchars(relative_time($post['created_at'])); ?></span>
                            </div>
                        </div>
                        <div class="post-content">
                            <p><?php echo htmlspecialchars($post['content']); ?></p>
                            <?php if (!empty($post['image_path'])): ?>
                                <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" style="max-width: 100%; height: auto;">
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No posts available.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <form id="uploadForm" method="post" action="ProfileCoverUpload.php" enctype="multipart/form-data">
        <input type="file" id="fileUpload" name="fileUpload" style="display: none;">
        <input type="hidden" id="uploadType" name="uploadType">
    </form>

    <script>
        document.getElementById('change-profile').addEventListener('click', function() {
            document.getElementById('uploadType').value = 'profile';
            document.getElementById('fileUpload').click();
        });

        document.getElementById('change-cover').addEventListener('click', function() {
            document.getElementById('uploadType').value = 'cover';
            document.getElementById('fileUpload').click();
        });

        document.getElementById('fileUpload').addEventListener('change', function() {
            document.getElementById('uploadForm').submit();
        });
    </script>
    <!-- <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f2f2f2;
}

.header {
    background-color: red;
    font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
    color: #fff;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content {
    display: flex;
    justify-content: space-between;
    width: 100%;
    align-items: center;
}

.logo a {
    display: flex;
    align-items: center;
    text-decoration: none; 
    color: inherit;
}

.logo img {
    width: 50px;
    height: auto;
    margin-right: 10px;
}

.logo span {
    font-size: 24px;
    font-weight: bold;
}

.nav-links a {
    color: white;
    margin-left: 20px;
    text-decoration: none;
    font-size: 18px; 
    padding: 0 5px;
}
.nav-links a:hover{
    color: gold;
}
/*
.nav {
    background-color: #630E15;
    padding: 8px 0;
}
.nav ul {
    list-style: none;
    margin: 0;
    padding-left: 20px;
    display: flex;
}*/


@media screen and (max-width: 629px) {
    .nav {
        padding: 10px 0;
        padding-right: 100px;
        padding-left: 100px;
    }

    .nav ul {
        justify-content: space-between;
    }

    .nav ul li a {  
        flex-direction: row;
    }

    .nav ul li a .fi {
        margin-bottom: 0;
        font-size: 24px;
    }

    .nav ul li a .nav-text {
        display: none;
    }
}

.nav ul li a {
    color: #fff;
    text-decoration: none;
}

main {
    max-width: 1000px;
    margin: 0 auto;
    background-color: white;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

#profile {
    position: relative;
}

.cover-photo {
    position: relative;
    height: 400px;
    overflow: hidden;
}

.cover-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-info {
    display: flex;
    padding: 1rem;
    margin-top: -50px;
    border-bottom: 1px solid #e0e0e0;
}

.user-details {
    margin-top: 50px;
    margin-left: 1rem;
}

.user-details h1 {
    margin-bottom: 0;
}

.user-role {
    color: #808080;
    margin-top: 5px;
}

.additional-details {
    padding: 30px;
    background-color: #f9f9f9;
}

.additional-details h2 {
    font-size: 1.2rem;
    margin-top: 0;
    margin-bottom: 1rem;
    color: #333;
}

.additional-details p {
    margin: 0.5rem 0;
}

.profile-photo {
    position: relative;
    margin-right: 1rem;
    margin-left: 1rem;
}

.profile-photo img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid white;
}

.change-photo {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: rgba(255, 255, 255, 0.8);
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    cursor: pointer;
}

#change-cover {
    top: 10px;
    right: 10px;
}

.user-details {
    flex-grow: 1;
}

#posts {
    padding: 1rem;
}

.post {
    margin-bottom: 1rem;
    padding: 1rem;
    background-color: #f9f9f9;
    border-radius: 4px;
}

.post-header {
    display: flex;
    align-items: center;
}

.profile-pic {
    margin-right: 7px;
}

.profile-pic img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.post-header .post-header-info h3 {
    font-size: 16px;
    margin: 0;
}

.post-header .post-header-info span {
    color: #808080;
    margin: 0;
    font-size: 12px;
    margin-top: 2px;
}
</style> -->
</body>
</html>
