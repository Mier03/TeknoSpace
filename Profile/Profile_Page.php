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
$default_profile_image = 'https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=';
$default_cover_photo = 'https://cit.edu/wp-content/uploads/2024/03/PNG_2nd-Semester_Website-Banner-2024.png';


if ($result->num_rows > 0) {
    $profile_row = $result->fetch_assoc();

    if (!empty($profile_row['profile_pic']) && file_exists($profile_row['profile_pic'])) {
        $profilePic = $profile_row['profile_pic'];
    } else {
        $profilePic = $default_profile_image;
    }

    if (!empty($profile_row['cover_photo'])) {
        $coverPhoto = $profile_row['cover_photo'];
    } else {
        $coverPhoto = $default_cover_photo;
    }
} else {
    // Default images if no profile found
    $profilePic = $default_profile_image;
    $coverPhoto = $default_cover_photo;

    // Insert default images into the profile table
    $insert = $conn->query("INSERT INTO profile (userId, profile_pic, cover_photo) VALUES ('$userId', '$profile_image', '$cover_photo')");
    if (!$insert) {
        die("Error inserting new profile: " . $conn->error);
    }
}


// Fetch user posts only if user is Faculty
$posts = [];
if ($userType === 'Faculty' || $userType === 'Admin') {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }

        .background-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
        }

        .background-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header {
            background-color: #1D0001;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            color: #fff;
            top: 0;
            width: 97.5%;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            z-index: 1000;
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

        .burger-icon {
            display: none;
        }

        .nav-links {
            margin-right: 1rem;
        }

        @media screen and (max-width: 490px) {
            .nav-links {
                display: none;
            }

            .burger-icon {
                display: block;
                font-size: 24px;
                cursor: pointer;
                margin-right: 1rem;
            }

            .nav-links.show {
                display: flex;
            }

            .nav {
                padding-right: 25px;
                padding-left: 25px;
            }

            .navmodal {
                display: none;
                position: fixed;
                z-index: 1000;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
            }
            
            .navmodal-content {
                display: block;
                background-color: #630F10;
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
                width: 80%;
                max-width: 300px;
            }
            .navmodal-content a {
                display: block;
                color: white;
                text-decoration: none;
                font-size: 18px;
                margin: 15px 0;
                text-align: center;
                transition: color 0.3s ease;
            }
            
            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: white;
                z-index: 999;
            }
            
            .overlay.active,
            .navmodal.active {
                display: block;
                padding: 10px;
                text-align: left;
            }

            .close {
                color: gold;
                float: right;
                font-size: 28px;
                font-weight: bold;
            }

            .close:hover,
            .close:focus {
                color: #888;
                text-decoration: none;
                cursor: pointer;
            }
            #posts h2 {
                margin-left:1rem;
            }
        }
        .navmodal {
            display: none;
        }
        .navmodal-content a:hover, 
        .navmodal-content a.active {
            color: gold;
        }
        .nav-links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-size: 18px;
            padding: 0 5px;
        }

        .nav-links a:hover {
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
            padding-top: 70px;
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
            background-color: white;
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

        #posts h2 {
            /* -webkit-text-stroke: 1px white; */
        }


        .post {
            background-color: #fff;
            border: 1px solid #dddfe2;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .profile-pic {
            margin-right: 7px;
        }

        .profile-pic img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .post-header-info {
            flex-grow: 1;
        }

        .post-header-info h3 {
            margin: 0;
            font-size: 16px;
        }

        .post-date {
            color: #808080;
            font-size: 12px;
        }

        .post-content {
            margin-bottom: 10px;
        }

        .post-content p {
            margin-top: 10;
        }

        .post-image {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
            border-radius: 8px;
        }

        .post-options {
            position: relative;
            margin-left: auto;
        }

        .post-options-btn {
            background: none;
            border: none;
            cursor: pointer;
        }

        .post-options-content {
            display: none;
            position: absolute;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }

        .post-options-content a {
            display: block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
        }

        .post-options-content a:hover {
            background-color: #f0f0f0;
        }

        .post-options-content.show {
            display: block;
        }
        /* screen resize start */
    
        @media screen and (max-width: 629px) {
            .cover-photo {
                height: 300px;
            }

            .profile-photo img {
                width: 120px;
                height: 120px;
            }

            .user-details h1 {
                font-size: 1.5rem;
            }

            .user-role {
                font-size: 0.9rem;
            }

            .additional-details {
                padding: 20px;
            }

            .additional-details h2 {
                font-size: 1rem;
                margin-left: 1rem;
            }

            .additional-details p {
                font-size: 0.9rem;
                margin-left: 1rem;
            }
            .post-options-btn {
                font-size: 1em; 
                padding: 3px; 
            }

            .post-options-btn i {
                font-size: 1.2rem !important; 
            }

            .post-options-content {
                min-width: 140px !important;
            }

            .post-options-content a {
                padding: 3px 6px;
                font-size: 0.9em;
            }

            .post-options-content a:before {
                font-size: 1em;
            }

            .toggle-found:before,
            .edit-post:before,
            .delete-post:before,
            .toggle-important:before {
                margin-right: 4px;
            }

            .important-badge {
                padding: 6px 8px !important;
                font-size: 0.8rem !important;
            }
        }

        @media screen and (max-width: 1030px) {
            #posts h2 {
                margin-left: 1rem;
            }
        }
        @media screen and (max-width: 1000px) {
            .post {
                border-radius: 0 !important;
                margin-bottom: 10px !important;
                margin-top: 0 !important;
            }
        }
        @media screen and (max-width: 490px) {
            .cover-photo {
                height: 200px;
            }

            .profile-photo img {
                width: 120px;
                height: 120px;
            }

            .profile-info {
                flex-direction: column;
                align-items: flex-start;
                margin-top: -100px;
            }

            .profile-photo {
                margin-left: 0;
                margin-bottom: 10px;
                position: relative;
            }

            .user-details {
                margin-top: 0;
                text-align: left;
            }

            .user-details h1 {
                font-size: 1.3rem;
                margin-top: 5px;
            }

            .user-role {
                font-size: 0.9rem;
            }

            .additional-details {
                padding: 15px;
            }

            .additional-details h2 {
                font-size: 1rem;
                margin-left: 1rem;
            }

            .additional-details p {
                font-size: 0.9rem;
                margin-left: 1rem;
            }

            .post-options-btn {
                font-size: 0.5em;
                padding: 2px;
            }

            .post-options-btn i {
                font-size: 1rem !important;
            }

            .post-options-content {
                min-width: 130px !important;
            }

            .post-options-content a {
                padding: 3px 6px;
                font-size: 0.8em;
            }

            .post-options-content a:before {
                font-size: 0.9em;
            }
            
            .toggle-found:before,
            .edit-post:before,
            .delete-post:before,
            .toggle-important:before {
                margin-right: 4px;
            }

            .important-badge {
                padding: 4px 6px !important;
                font-size: 0.7rem !important;
            }

            #posts h2 {
                font-size: 1.2rem;
            }

        }
        /* screen resize end */

        /* important post - START */
        .important-post {
            border-left: 5px solid red;
        }

        .important-badge {

            color: red;
            padding: 2px 5px;
            margin-right: 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 0.9em;

        }

        /* important post - END */

        .post-options {
            position: relative;
            display: inline-block;
        }

        .post-options-btn {
            background: none;
            border: none;
            cursor: pointer;
            cursor: pointer;
            padding: 5px;
            font-size: 1.2em;
            color: #800000;
        }

        .post-options-btn i {
            font-size: 1.5rem;
            color: #333;
        }

        .post-options-content {
            display: none;
            position: absolute;
            background-color: #fff;
            min-width: 180px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            padding: 8px;
            top: 30px;
            right: 0;
            z-index: 1;
            display: none;
        }

        .post-options-content.show {
            display: block;
        }

        .post-options-content a {
            display: block;
            padding: 6px 10px;
            color: #333;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .post-options-content a:hover {
            background-color: #f0f0f0;
        }


        .edit-post,
        .delete-post,
        .toggle-important,
        .toggle-found {
            border-bottom: 1px solid #e0e0e0;
        }

        .toggle-found:before,
        .edit-post:before,
        .delete-post:before,
        .toggle-important:before {
            margin-right: 8px;
            font-family: 'boxicons';
            font-size: 1.1em;
            vertical-align: middle;
        }
        .toggle-found:before {
            content: '\e9c1';
            color: #800000;
        }
        .edit-post:before {
            /* pa edit sa iconnn hehe  or remove nalang if di ninyo bet mag icon*/
            content: '\ea8b';

            color: #800000;
        }

        .delete-post:before {
            /* pa edit sa iconnn hehe */
            content: '\eb78';

            color: #800000;
        }

        .toggle-important:before {
            /* pa edit sa iconnn hehe */
            content: '\ea0e';

            color: #800000;
        }

        .important-badge {
            display: inline-block;
            padding: 4px 8px;
            background-color: #DC143C;
            color: #fff;
            font-weight: bold;
            font-size: 0.8rem;
            margin-right: 6px;
            border-radius: 4px;
        }
        .post-content h1 {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: rgb(204, 35, 35);
            padding: 0 !important;
            margin-bottom: 0;
            margin-top: 1rem !important; 
            font-family:'Georgia';
        }
        /* LOGOUT MODAL */
        .logout-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .logout-modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 250px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000000;
            z-index: 2001;
            font-family: "Outfit", sans-serif;
        }

        .logout-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
          /* Image Clickable */
          .post-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            cursor: pointer;
        }
        #imageModal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
        }

        #imageModal .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        #imageModal .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        #imageModal .close:hover,
        #imageModal .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
        #imageModal{
            display: none;
        }
    </style>
    <!-- <link rel="stylesheet" href="Profile_styles.css"> -->
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-rounded/css/uicons-solid-rounded.css'>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="background-container">
        <img src="../images/signin-bg.png" alt="Background Image">
    </div>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <?php
                if ($userType === 'Student') {
                    echo '<a href="../Users/studentHomepage.php">';
                } elseif ($userType === 'Faculty') {
                    echo '<a href="../Users/facultyHomepage.php">';
                } elseif ($userType === 'Admin') {
                    echo '<a href="../Users/ADMIN.php">';
                }
                ?>
                <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span></a>
            </div>
            <div class="nav-links">
                <a href="Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>
                <a href="#" onclick="showLogoutModal(); return false;">Log Out</a>
            </div>
            <div id="logoutModal" class="logout-modal">
                <div class="logout-modal-content">
                    <img src="../images/check_gif.webp" alt="Success" class="logout-icon">
                    <p>Logged out successfully</p>
                </div>
            </div>
            <div class="burger-icon">
                <i class='bx bx-menu burger-icon' onclick="toggleMenu()"></i>
            </div>
        </div>
    </header>
    <!-- navmodal -->
    <div id="navModal" class="navmodal">
        <div class="navmodal-content">
            <span class="close" onclick="toggleMobileMenu()">&times;</span>
                <a href="../Profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i><span class="nav-link">      Profile</span></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i><span class="nav-link">     Notifications</span></a>
                <!-- not working -->
                <a href="#" onclick="showLogoutModal(); return false;"><i class='bx bx-exit' ></i>     Log Out</a>
        </div>
    </div>
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

        <?php if ($userType === 'Student') : ?>
            <section id="posts">
                <h2>Posts</h2>
                <div id="post-list">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <div class="post" data-post-id="<?php echo htmlspecialchars($post['id']); ?>">
                                <div class="post-header">
                                    <div class="profile-pic">
                                        <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                                    </div>
                                    <div class="post-header-info">
                                        <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                                        <span class="post-date"><?php echo htmlspecialchars(relative_time($post['created_at'])); ?></span>
                                    </div>
                                    <div class="post-options">
                                        <button class="post-options-btn"><i class='bx bx-dots-horizontal-rounded'></i></button>
                                        <div class="post-options-content">
                                            <a href="#" class="edit-post">Edit Post</a>
                                            <a href="#" class="delete-post">Delete Post</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-content">
                                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                                    <?php if (!empty($post['image_path'])) : ?>
                                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" class="post-image" data-full-image="<?php echo htmlspecialchars($post['image_path'])?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No posts available.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
        <?php if ($userType === 'Faculty') : ?>
            <section id="posts">
                <h2>Posts</h2>
                <div id="post-list">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <?php
                            $postClass = $post['is_important'] ? 'important-post' : '';
                            ?>
                            <div class="post <?php echo $postClass; ?>" data-post-id="<?php echo htmlspecialchars($post['id']); ?>">
                                <div class="post-header">
                                    <div class="profile-pic">
                                        <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                                    </div>

                                    <div class="post-header-info">
                                        <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                                        <span class="post-date"><?php echo htmlspecialchars(relative_time($post['created_at'])); ?></span>
                                    </div>

                                    <?php if ($post['is_important']) : ?>
                                        <?php echo '<span class="important-badge"><strong>! IMPORTANT</strong></span>'; ?>
                                    <?php endif; ?>


                                    <div class="post-options">
                                        <button class="post-options-btn"><i class='bx bx-dots-horizontal-rounded'></i></button>
                                        <div class="post-options-content">
                                            <a href="#" class="edit-post">Edit Post</a>
                                            <a href="#" class="delete-post">Delete Post</a>

                                            <?php if ($post['posttype'] === 'Announcement' || $post['posttype'] === 'Maintenance') : ?>
                                                <?php if ($post['is_important']) : ?>
                                                    <a href="#" class="toggle-important" data-action="remove">Remove Important</a>
                                                <?php else : ?>
                                                    <a href="#" class="toggle-important" data-action="make">Make Important</a>
                                                <?php endif; ?>
                                            <?php elseif ($post['posttype'] === 'Lost & Found') : ?>
                                                <a href="#" class="toggle-found" data-action="found" onclick="toggleFound(<?php echo $post['id']; ?>, 'found', this);">Found</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="post-content">
                                <?php if ($post['posttype'] === 'Lost & Found') : ?>
                                    <div>
                                        <?php if ($post['status'] === 'found') : ?>
                                            <h1 style="color: #800000">! FOUND</h1>
                                        <?php else : ?>
                                            <h1 style="color: red">! LOST</h1>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                                    <?php if (!empty($post['image_path'])) : ?>
                                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" class="post-image" data-full-image="<?php echo htmlspecialchars($post['image_path'])?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        <?php endforeach; ?>
                    <?php else : ?>
                        <p>No posts available.</p>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($userType === 'Admin') : ?>
            <section id="posts">
                <h2>Posts</h2>
                <div id="post-list">
                    <?php if (!empty($posts)) : ?>
                        <?php foreach ($posts as $post) : ?>
                            <?php
                            $postClass = $post['is_important'] ? 'important-post' : '';
                            ?>
                            <div class="post <?php echo $postClass; ?>" data-post-id="<?php echo htmlspecialchars($post['id']); ?>">
                                <div class="post-header">
                                    <div class="profile-pic">
                                        <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                                    </div>

                                    <div class="post-header-info">
                                        <h3><?php echo htmlspecialchars($post['username']); ?></h3>
                                        <span class="post-date"><?php echo htmlspecialchars(relative_time($post['created_at'])); ?></span>
                                    </div>

                                    <?php if ($post['is_important']) : ?>
                                        <?php echo '<span class="important-badge"><strong>! IMPORTANT</strong></span>'; ?>
                                    <?php endif; ?>


                                    <div class="post-options">
                                        <button class="post-options-btn"><i class='bx bx-dots-horizontal-rounded'></i></button>
                                        <div class="post-options-content">
                                            <a href="#" class="edit-post">Edit Post</a>
                                            <a href="#" class="delete-post">Delete Post</a>

                                            <?php if ($post['posttype'] === 'Announcement' || $post['posttype'] === 'Maintenance') : ?>
                                                <?php if ($post['is_important']) : ?>
                                                    <a href="#" class="toggle-important" data-action="remove">Remove Important</a>
                                                <?php else : ?>
                                                    <a href="#" class="toggle-important" data-action="make">Make Important</a>
                                                <?php endif; ?>
                                            <?php elseif ($post['posttype'] === 'Lost & Found') : ?>
                                                <a href="#" class="toggle-found" data-action="found">Found</a>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="post-content">
                                <?php if ($post['posttype'] === 'Lost & Found') : ?>
                                    <div>
                                        <?php if ($post['status'] === 'found') : ?>
                                            <h1 style="color: #800000">! FOUND</h1>
                                        <?php else : ?>
                                            <h1 style="color: red">! LOST</h1>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                                    <?php if (!empty($post['image_path'])) : ?>
                                        <img src="<?php echo htmlspecialchars($post['image_path']); ?>" alt="Post Image" class="post-image" data-full-image="<?php echo htmlspecialchars($post['image_path'])?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
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
        <!-- Interactive Image -->
        <div id="imageModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="fullImage">
        </div>
    <script>
        /**LOGOUT MODAL */
        function showLogoutModal() {
        console.log("Logout function called");
        var modal = document.getElementById('logoutModal');
        var navModal = document.getElementById('navModal');
        if (modal) {
            modal.style.display = "block";
            // Close Burger Icon
            if (navModal) {
                navModal.classList.remove("active");
            }
            setTimeout(function() {
                modal.style.display = "none";
                window.location.href = "../aboutUs.php"; 
            }, 1250);
        } else {
            console.error("Logout modal not found"); 
        }
        }

    document.addEventListener('DOMContentLoaded', function() {
        var logoutLink = document.querySelector('#navModal a[onclick*="showLogoutModal"]');
        if (logoutLink) {
            logoutLink.addEventListener('click', function(e) {
                e.preventDefault();
                showLogoutModal();
            });
        }

        var burgerIcon = document.querySelector(".burger-icon");
            var navLinks = document.querySelector(".nav-links");
            var modal = document.getElementById('navModal');
            var overlay = document.querySelector(".overlay");
            var closeBtn = document.querySelector(".close");

            burgerIcon.addEventListener("click", function () {
                modal.classList.toggle("active");
                overlay.classList.toggle("active");
            });

            function closeModal() {
                modal.classList.remove("active");
                overlay.classList.remove("active");
            }

            closeBtn.addEventListener("click", closeModal);
            overlay.addEventListener("click", closeModal);
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('post-list').addEventListener('click', function(event) {
                var button = event.target.closest('.post-options-btn');
                if (button) {
                    button.nextElementSibling.classList.toggle('show');
                }



                if (event.target.classList.contains('edit-post')) {
                    event.preventDefault();
                    var post = event.target.closest('.post');
                    var postId = post.dataset.postId;
                    var contentElement = post.querySelector('.post-content p');
                    var originalContent = contentElement.textContent;

                    contentElement.innerHTML = `
                    <textarea>${originalContent}</textarea>
                    <button class="save-edit">Save</button>
                    <button class="cancel-edit">Cancel</button>
                `;

                    post.querySelector('.save-edit').addEventListener('click', function() {
                        var newContent = post.querySelector('textarea').value;
                        saveEdit(postId, newContent, contentElement, originalContent);
                    });

                    post.querySelector('.cancel-edit').addEventListener('click', function() {
                        contentElement.innerHTML = originalContent;
                    });


                }

                if (event.target.classList.contains('delete-post')) {
                    event.preventDefault();
                    var post = event.target.closest('.post');
                    var postId = post.dataset.postId;
                    if (confirm('Are you sure you want to delete this post?')) {
                        deletePost(postId, post);
                    }


                }

                if (event.target.classList.contains('toggle-important')) {
                    event.preventDefault();
                    const postId = event.target.closest('.post').dataset.postId;
                    const action = event.target.dataset.action;
                    toggleImportance(postId, action, event.target);

                }

                if (event.target.classList.contains('toggle-found')) {
                    event.preventDefault();
                    const postId = event.target.closest('.post').dataset.postId;
                    const action = event.target.dataset.action;
                    toggleFound(postId, action, event.target);

                }
            });

            function toggleImportance(postId, action, toggleLink) {
                fetch('update_importance.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `postId=${postId}&action=${action}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const post = toggleLink.closest('.post');
                            const importantBadge = post.querySelector('.important-badge');

                            if (action === 'make') {
                                if (!importantBadge) {
                                    const newBadge = document.createElement('span');
                                    newBadge.classList.add('important-badge');
                                    newBadge.innerHTML = '<strong>! IMPORTANT</strong>';
                                    post.querySelector('.post-header-info').after(newBadge);
                                }
                                toggleLink.innerText = 'Remove Important';
                                toggleLink.setAttribute('data-action', 'remove');
                                post.classList.add('important-post');
                            } else {
                                if (importantBadge) {
                                    importantBadge.remove();
                                }
                                toggleLink.innerText = 'Make Important';
                                toggleLink.setAttribute('data-action', 'make');
                                post.classList.remove('important-post');
                            }
                        } else {
                            alert('Failed to update importance');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function toggleFound(postId, action, toggleLink) {
                if (confirm('Is this item found?')) {
                    fetch('update_status.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `postId=${postId}&action=${action}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const post = toggleLink.closest('.post');
                            const lostHeading = post.querySelector('h1');

                            if (lostHeading) {
                                lostHeading.innerText = '! FOUND';
                                lostHeading.style.color = '#800000'; // Optionally change the color if needed
                            }
                        } else {
                            alert('Failed to update status: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error: ' + error.message);
                    });
                }
            }

            
            function saveEdit(postId, newContent, contentElement, originalContent) {
                fetch('edit_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `postId=${postId}&content=${encodeURIComponent(newContent)}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            contentElement.innerHTML = newContent;
                        } else {
                            alert('Failed to save edit');
                            contentElement.innerHTML = originalContent;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        contentElement.innerHTML = originalContent;
                    });
            }

            function deletePost(postId, postElement) {
                fetch('delete_post.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: `postId=${postId}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            postElement.remove();
                        } else {
                            alert('Failed to delete post');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

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

            document.addEventListener('click', function(event) {
                if (!event.target.closest('.post-options')) {
                    document.querySelectorAll('.post-options-content').forEach(function(options) {
                        options.classList.remove('show');
                    });
                }
            });
                    //Image Modal
            var imageModal = document.getElementById('imageModal');
            var modalImg = document.getElementById("fullImage");
            var span = document.getElementsByClassName("close")[0];

            document.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('post-image')) {
                    imageModal.style.display = "block";
                    modalImg.src = e.target.getAttribute('data-full-image');
                }
            });

            span.onclick = function() {
                imageModal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == imageModal) {
                    imageModal.style.display = "none";
                }
            }
                });
    </script>

</body>

</html>