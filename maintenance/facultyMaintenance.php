<?php
include('../Users/auth.php');
include('../config.php');
include('../helper.php');
checkLogin();
checkUserRole('Faculty');

// Check if 'firstName' or 'lastName' is undefined in the session
if (!isset($_SESSION['firstName']) || !isset($_SESSION['lastName'])) {
    header("Location: ../USERS/verifyFaculty.php");
    exit();
}
if (!isset($_SESSION['valid'])) {
    header("Location: ../Login-Signup/login.php");
    exit();
}
$userId = $_SESSION['id'];
$userName = $_SESSION['firstName'];
$fullName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];

$sql = "SELECT * FROM profile WHERE userId = '$userId'";
$result = $conn->query($sql);
$default_profile_image = 'https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=';


if ($result->num_rows > 0) {
    $profile_row = $result->fetch_assoc();

    if (!empty($profile_row['profile_pic']) && file_exists($profile_row['profile_pic'])) {
        $profilePic = $profile_row['profile_pic'];
    } else {
        $profilePic = $default_profile_image;
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teknospace</title>
    <link rel="stylesheet" href="../Users/Admin_styles.css">
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-regular-straight/css/uicons-regular-straight.css'>

    
</head>

<body>
    <div class="background-container">
        <img src="../images/signin-bg.png" alt="Background Image">
    </div>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                <span>TEKNOSPACE</span>
            </div>
            <div class="nav-links">
                <a href="../Profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>
                <a href="#" onclick="showLogoutModal(); return false;">Log Out</a>
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
                <a href="#" onclick="showLogoutModal(); return false;"><i class='bx bx-exit' ></i>     Log Out</a>
        </div>
    </div>


    <nav class="nav">
        <ul>
            <li><a href="../Users/facultyHomepage.php" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="facultyMaintenance.php" class="icon" style="color: #fff3b0; background-color: #8B1818;"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="../LostAndFound/facultyLostFound.php" class="icon"><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
        </ul>
    </nav>
    <main class="main">
        <div class="nav-name">Maintenance</div>
        <div class="create-post">
            <div class="post-header">
                <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                <div class="post-header-info">
                    <h3><?php echo $fullName ?></h3>
                </div>
            </div>
            <div class="post-space" id="postInput">
                <p>What's on your mind, <?php echo $userName ?>?</p>
            </div>
        </div>
        <!-- Pop-up Create Post -->
        <div id="postModal" class="postmodal">
            <div class="postmodal-content">
                <span class="close">&times;</span>
                <h2>Create post</h2> 
                <div class="post-header">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                    <div class="post-header-info">
                        <h3><?php echo $fullName ?></h3>
                        <select id="postAudience">
                            <option value="All students">All students</option>
                            <option value="Department">Department</option>
                        </select>

                    </div>
                </div>
                <div class="post-input">
                    <div id="postContent" contenteditable="true" placeholder="What's on your mind, <?php echo $userName ?>?"></div>
                </div>


                <div class="post-options">
                    <div class="add-picture">
                        <i class="fi fi-br-picture"></i>
                        <input type="file" id="postImage" accept="image/*" style="display: none;">
                        <label for="postImage" class="custom-file-upload">Add Picture or Video</label>
                    </div>
                    <div class="important">
                        <label class="switch">
                            <input type="checkbox"  id="importantToggle">
                            <span class="slider"></span>
                        </label>
                        <span class="label-text">IMPORTANT</span>
                    </div>
                </div>
                <button class="btn-post" id="submitPost">Post</button>
            </div>
        </div>
        <div class="posts">
        </div>
        <div id="logoutModal" class="logout-modal">
            <div class="logout-modal-content">
                <img src="../images/check_gif.webp" alt="Success" class="logout-icon">
                <p>Logged Out Successfully</p>
            </div>
        </div>
        
    <!--Notification Modal-->
        <div id="notificationModal" class="notification-modal">
            <div class="notification-content">
                <span class="close-notification">&times;</span>
                <p>No new notifications</p>
            </div>
        </div>
           <!-- Interactive Image -->
           <div id="imageModal" class="modal">
            <span class="close">&times;</span>
            <img class="modal-content" id="fullImage">
        </div>
    </main>
    <script src="post.js"></script>
    <script src="comment.js"></script>
    <script>      
        // BURGER ICON
        document.addEventListener("DOMContentLoaded", function () {
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
            window.showLogoutModal = function() {
                console.log("Logout function called"); // Debugging line
                var modal = document.getElementById('logoutModal');
                if (modal) {
                    modal.style.display = "block";
                    setTimeout(function() {
                        modal.style.display = "none";
                        window.location.href = "../aboutUs.php"; // Adjust this URL as needed
                        logout();
                    }, 1250);
                } else {
                    console.error("Logout modal not found"); // Debugging line
                }
            };
        });
        function logout() {
            fetch('../Users/logout.php', {
                method: 'POST',
            }).then(response => {
                if (response.ok) {
                    window.location.href = '../aboutUs.php';
                }
            }).catch(error => {
                console.error('Logout failed:', error);
            });
        }
            /**Notification Modal */
            document.addEventListener('DOMContentLoaded', function() {
            const notificationIcon = document.querySelector('a[href="#notif"] i');
            const notificationModal = document.getElementById('notificationModal');

        function openNotificationModal(e) {
            e.preventDefault();
            notificationModal.style.display = 'block';
            notificationIcon.classList.add('active');
            notificationIcon.classList.remove('fi-br-bell-notification-social-media');
            notificationIcon.classList.add('fi-br-cross-small');
            notificationIcon.removeEventListener('click', openNotificationModal);
            notificationIcon.addEventListener('click', closeNotificationModal);
        }

        function closeNotificationModal(e) {
            e.preventDefault();
            notificationModal.style.display = 'none';
            notificationIcon.classList.remove('active');
            notificationIcon.classList.remove('fi-br-cross-small');
            notificationIcon.classList.add('fi-br-bell-notification-social-media');
            notificationIcon.removeEventListener('click', closeNotificationModal);
            notificationIcon.addEventListener('click', openNotificationModal);
        }

        notificationIcon.addEventListener('click', openNotificationModal);

        window.addEventListener('click', function(e) {
            if (e.target == notificationModal) {
                closeNotificationModal(e);
        }
    });
});

    </script>
    <style>
        .background-container img {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
            object-fit: cover;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 32px;
            height: 20px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 12px;
            width: 12px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: gold;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(12px);
            -ms-transform: translateX(12px);
            transform: translateX(12px);
        }

        .label-text {
            font-size: 14px;
            margin-left: 5px;
            vertical-align: middle;
        }

        .background-container img {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.5;
            object-fit: cover;
        }

        .post-options {
            display: flex;
            justify-content: space-between;
            /* Align items to the left and right */
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .post-options .add-picture {
            display: flex;
            align-items: center;
            /* Align items vertically */
        }

        .post-options .add-picture i {
            margin-right: 10px;
            /* Adjust the margin between the icon and the label */
            font-size: 16px;
            color: gold;
        }

        /* Placeholder style for contenteditable div */
        #postContent:empty:before {
            content: attr(placeholder);
            color: grey;
        }

        /* Style for the inserted image */
        #postContent img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 10px 0;
        }

        /* MODAL */
        .logout-modal {
            display: none;
            position: fixed;
            z-index: 1000;
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
    </style>
</body>

</html>