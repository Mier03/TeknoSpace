<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

$userName = $_SESSION['username'];
$fullName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
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
    <link rel="stylesheet" href="../Users/Admin_styles.css">
    <link rel="icon" type="image/x-icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTO7IQ84s9PNogtYXeoy7CsfrMWOEWM6VCc1lwv02D67M0ji_SCx9-MgL3vEECexc7UnVU&usqp=CAU">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
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

        /* Style for the contenteditable div */
        #postContent {
            outline: none;
            width: 95%;
            height: 100px;
            background-color: whitesmoke;
            font-family: monospace;
            margin: 0 0;
            border: none;
            color: black;
            resize: vertical;
            font-size: 18px;
            border-radius: 10px;
            padding: 5px;
            overflow-y: auto;
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
    </style>

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
    <div id="navModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="toggleMenu()">&times;</span>
            <a href="../Profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i><span class="nav-link">      Profile</span></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i><span class="nav-link">     Notifications</span></a>
                <a href="#" onclick="showLogoutModal(); return false;"><i class='bx bx-exit' ></i>     Log Out</a>
        </div>
    </div>

    <nav class="nav">
        <ul>
            <li><a href="../Users/ADMIN.php" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="../Maintenance/adminMaintenance.php" class="icon"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="adminLostFound.php" class="icon"><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
            <li>
                <a href="#manageAccount" class="icon manage-account"><i class="fas fa-user-cog"></i><span class="nav-text">Manage Account</span></a>
            </li>

        </ul>
    </nav>

    <div id="manageAccountModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Manage Accounts</h2>
            <button onclick="goToAllAccounts()">All Accounts</button>
            <button onclick="goToVerifyAccounts()">Verify Accounts</button>
        </div>
    </div>


    <main class="main">
        <div class="create-post">
            <div class="post-header">
                <img src="https://static.thenounproject.com/png/3918329-200.png" alt="Profile Image">
                <div class="post-header-info">
                    <h3><?php echo $fullName ?></h3>
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
                        <h3><?php echo $fullName ?></h3>
                        <select id="postAudience">
                            <option value="All students">All students</option>
                            <option value="Department">Department</option>
                        </select>
                    </div>
                </div>
                <div class="post-input" id="postInput">
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
                            <input type="checkbox">
                            <span class="slider"></span>
                        </label>
                        <span class="label-text">IMPORTANT</span>
                    </div>
                </div>
                <button class="btn-post" id="submitPost">Post</button>
            </div>
        </div>
        <div id="logoutModal" class="logout-modal">
            <div class="logout-modal-content">
                <img src="../images/check_gif.webp" alt="Success" class="logout-icon">
                <p>Logged out successfully</p>
            </div>
        </div>


        <!-- Post -->
        <div class="posts">

        </div>


    </main>

    <script src="post.js"></script>
    <script src="comment.js"></script>
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
    </style>


    <script>
        function toggleMenu() {
            const modal = document.getElementById('navModal');
            const closeButton = document.querySelector('.close');
            if (modal.style.display === 'block') {
                modal.style.display = 'none';
            } else {
                modal.style.display = 'block';
            }

            closeButton.addEventListener('click', toggleMenu);
        }
        document.addEventListener('DOMContentLoaded', function() {
            window.showLogoutModal = function() {
                console.log("Logout function called"); // Debugging line
                var modal = document.getElementById('logoutModal');
                if (modal) {
                    modal.style.display = "block";
                    setTimeout(function() {
                        modal.style.display = "none";
                        window.location.href = "../aboutUs.php"; // Adjust this URL as needed
                    }, 1250);
                } else {
                    console.error("Logout modal not found"); // Debugging line
                }
            };
        });
        const modal = document.getElementById("manageAccountModal");
        const manageAccountLink = document.querySelector('.manage-account');
        const closeBtn = document.querySelector('.modal-content .close');

        manageAccountLink.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = "block";
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = "none";
        });

        window.addEventListener('click', function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        function goToAllAccounts() {
            modal.style.display = "none";
            window.location.href = "../Users/allAccounts.php";
        }

        function goToVerifyAccounts() {
            modal.style.display = "none";
            window.location.href = "../Users/verify.php";
        }

        const postModal = document.getElementById("postModal");
        const postModalCloseBtn = document.querySelector('#postModal .close');

        function openPostModal() {
            postModal.style.display = "block";
        }

        function closePostModal() {
            postModal.style.display = "none";
        }

        postModalCloseBtn.addEventListener('click', closePostModal);

        window.addEventListener('click', function(event) {
            if (event.target == postModal) {
                postModal.style.display = "none";
            }
        });
    </script>
</body>

</html>