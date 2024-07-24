<?php

include('auth.php');
include('../config.php');
include('../helper.php');
checkLogin();

// Check if 'firstName' or 'lastName' is undefined in the session
if (!isset($_SESSION['firstName']) || !isset($_SESSION['lastName'])) {
    header("Location: ../users/verifyFaculty.php");
    exit();
}

if (!isset($_SESSION['valid'])) {
    header("Location: ../login-signup/login.php");
    exit();
}
$userId = $_SESSION['id'];
$userName = $_SESSION['firstName'];
$fullName = $_SESSION['firstName'] . ' ' . $_SESSION['lastName'];
$userType = $_SESSION['userType'];

$schoolUpdatesUrl = '';

switch ($userType) {
    case 'Student':
        $schoolUpdatesUrl = 'studentHomepage.php';
        break;
    case 'Admin':
        $schoolUpdatesUrl = 'admin.php';
        break;
    case 'Faculty':
        $schoolUpdatesUrl = 'facultyHomepage.php';
        break;
}

$maintenanceUrl = '';

switch ($userType) {
    case 'Student':
        $maintenanceUrl = '../maintenance/studentMaintenance.php';
        break;
    case 'Admin':
        $maintenanceUrl = '../maintenance/adminMaintenance.php';
        break;
    case 'Faculty':
        $maintenanceUrl = '../maintenance/facultyMaintenance.php';
        break;
}

$lost_foundUrl = '';

switch ($userType) {
    case 'Student':
        $lost_foundUrl = '../lostandfound/studentLostFound.php';
        break;
    case 'Admin':
        $lost_foundUrl = '../lostandfound/adminLostFound.php';
        break;
    case 'Faculty':
        $lost_foundUrl = '../lostandfound/facultyLostFound.php';
        break;
}

$sql = "SELECT * FROM profile WHERE userId = '$userId'";
$result = $conn->query($sql);
$profilePic= 'https://media.istockphoto.com/id/1327592449/vector/default-avatar-photo-placeholder-icon-grey-profile-picture-business-man.jpg?s=612x612&w=0&k=20&c=yqoos7g9jmufJhfkbQsk-mdhKEsih6Di4WZ66t_ib7I=';


if ($result->num_rows > 0) {
    $profile_row = $result->fetch_assoc();

    if (!empty($profile_row['profile_pic']) && file_exists($profile_row['profile_pic'])) {
        $profilePic = $profile_row['profile_pic'];
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main Homepage</title>
    <link rel="icon" href="../images/urlicon.png" type="image/x-icon">
    <link rel="stylesheet" href="Admin_styles.css">
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-solid-straight/css/uicons-solid-straight.css'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-bold-rounded/css/uicons-bold-rounded.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-thin-straight/css/uicons-thin-straight.css'>
    <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.4.2/uicons-regular-straight/css/uicons-regular-straight.css'>
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
                    <img src="../images/teknospace-logo.jpg" alt="Teknospace Logo">
                    <span>TEKNOSPACE</span>
                </div>
                <div class="nav-links">
                    <a href="../profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i></a>
                    <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>
                    <a href="#" onclick="showLogoutModal(); return false;">Log Out</a>
                </div>
                <div class="burger-icon">
                    <i class='bx bx-menu burger-icon'></i>
                </div>
            </div>
    </header>
        <!-- navmodal -->
    <div id="navModal" class="navmodal">
        <div class="navmodal-content">
            <span class="close" >&times;</span>
            <a href="../profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i><span class="nav-link">      Profile</span></a>
                <a href="#notif" class="icon"><i class="fi fi-br-bell-notification-social-media"></i><span class="nav-link">     Notifications</span></a>
                <a href="#" onclick="showLogoutModal(); return false;"><i class='bx bx-exit' ></i>     Log Out</a>
        </div>
    </div>
    <nav class="nav">
        <ul>
            <li><a href="<?php echo $schoolUpdatesUrl; ?>" class="icon" ><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="<?php echo $maintenanceUrl; ?>" class="icon" ><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="<?php echo $lost_foundUrl; ?>" class="icon" ><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
            <?php if ($userType === 'Admin') : ?>
                <li><a href="#manageAccount" class="icon manage-account"><i class="fi fi-ss-user-gear"></i><span class="nav-text">Manage Account</span></a></li>
            <?php endif; ?>
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
    <div class="gif-container">
        <img src="../images/dancing-tiger.gif" alt="Welcome GIF" class="welcome-gif" onerror="this.onerror=null; this.src='https://i.pinimg.com/originals/e4/26/ae/e426aee03c5bc7ec7bf04c3ceecd4315.gif';">
    </div>
    <div class="welcome-message">
        <h1>Welcome Back to TeknoSpace, <?php echo $userName; ?>!</h1>
    </div>
    <div class="gif-container">
        <img src="../images/dancing-tiger.gif" alt="Welcome GIF" class="welcome-gif" onerror="this.onerror=null; this.src='https://i.pinimg.com/originals/e4/26/ae/e426aee03c5bc7ec7bf04c3ceecd4315.gif';">
    </div>

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

        const modal = document.getElementById("manageAccountModal");
        const manageAccountLink = document.querySelector('.manage-account');
        const closeBtn = document.querySelector('.modal-content .close');

        manageAccountLink.addEventListener('click', function (e) {
            e.preventDefault();
            modal.style.display = "block";
        });

        closeBtn.addEventListener('click', function () {
            modal.style.display = "none"; 
        });

        window.addEventListener('click', function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        });

        function goToAllAccounts() {
            modal.style.display = "none";
            window.location.href = "allAccounts.php"; 
        }

        function goToVerifyAccounts() {
            modal.style.display = "none";
            window.location.href = "verify.php"; 
        }

        //logout
        function showLogoutModal() {
            var modal = document.getElementById('logoutModal');
            modal.style.display = "block";
            
            setTimeout(function() {
                modal.style.display = "none";
                window.location.href='../aboutUs.php';
                logout();
            }, 1500);
        }
        function logout() {
            fetch('logout.php', {
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

        .main {
            font-family: 'Montserrat', sans-serif;
            color: #800000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
        }

        .welcome-message {
            max-width: 80%;
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 2px solid #800000;
            
        }

        .welcome-message h1 {
            font-size: 3em;
            margin-bottom: 20px;
            color: #800000;
        }
        .gif-container {
            max-width: 500px; 
            margin: 20px auto;
            text-align: center;
        }

        .welcome-gif {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        @media (max-width: 768px) {
            .welcome-message {
                max-width: 90%;
                padding: 20px; 
            }
            .welcome-message h1 {
                font-size: 2em; 
            }
        }
        @media (max-width: 600px) {
            .welcome-message {
                max-width: 80%;
                padding: 20px; 
            }
            .welcome-message h1 {
                font-size: 1.5em; 
            }
        }
        @media (max-width: 00px) {
            .welcome-message {
                max-width: 80%;
                padding: 20px; 
            }
            .welcome-message h1 {
                font-size: 1.5em; 
            }
        }
        @media (max-width: 768px) {
            .gif-container {
                max-width: 90%;
                margin: 20px auto;
            }
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
</body>

</html>