
<?php
include('../Users/auth.php');
checkLogin();
checkUserRole('Student');

if (!isset($_SESSION['valid'])) {
    header("Location: ../Login-Signup/login.php");
    exit();
}
$userName = $_SESSION['firstName'];
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
    <link rel="stylesheet" href="../Users/Student_styles.css">
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
                <a href="#" onclick="showLogoutModal()">Log Out</a>
            </div>
            <div class="burger-icon">
                <i class='bx bx-menu burger-icon' onclick="toggleMenu()"></i>
            </div>
        </div>
    </header>
    <div id="navModal" class="navmodal">
        <div class="navmodal-content">
            <span class="close" onclick="toggleMenu()">&times;</span>
            <a href="../Profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i><span class="nav-link">      Profile</span></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i><span class="nav-link">     Notifications</span></a>
                <a href="#" onclick="showLogoutModal(); return false;"><i class='bx bx-exit' ></i>     Log Out</a>
        </div>
    </div>
    <nav class="nav">
        <ul>
            <li><a href="../Users/studentHomepage.php" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="../Maintenance/studentMaintenance.php" class="icon"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="studentLostFound.php" class="icon" style="color: #fff3b0; background-color: #8B1818;" ><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
        </ul>
    </nav>
    <main class="main">
        <div class="posts">
        </div>
        <div id="logoutModal" class="logout-modal">
                <div class="logout-modal-content">
                    <img src="../images/check_gif.webp" alt="Success" class="logout-icon">
                    <p>Logged Out Successfully</p>
                </div>
        </div>
        <div id="notificationModal" class="notification-modal">
            <div class="notification-content">
                <p>No new notifications</p>
            </div>
        </div>
    </main>
    
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
                console.log("Logout function called");
                var modal = document.getElementById('logoutModal');
                if (modal) {
                    modal.style.display = "block";
                    setTimeout(function() {
                        modal.style.display = "none";
                        window.location.href = "../aboutUs.php";
                        logout();
                    }, 1250);
                } else {
                    console.error("Logout modal not found");
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
    </style>
</body>

</html>
