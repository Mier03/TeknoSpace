<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

include('../config.php');

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
    $profilePic = "https://st.depositphotos.com/2101611/3925/v/600/depositphotos_39258143-stock-illustration-businessman-avatar-profile-picture.jpg";
    $coverPhoto = "https://cit.edu/wp-content/uploads/2024/03/PNG_2nd-Semester_Website-Banner-2024.png";
    // Insert default images into the profile table
    $insert = $conn->query("INSERT INTO profile (userId, profile_pic, cover_photo) VALUES ('$userId', '$profilePic', '$coverPhoto')");
    if (!$insert) {
        die("Error inserting new profile: " . $conn->error);
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
    <main>
        <section id="profile">
            <div class="cover-photo">
                <img src="<?php echo $coverPhoto; ?>" alt="Cover Photo">
                <button class="change-photo" id="change-cover"><i class="fas fa-camera"></i></button>
            </div>
            <div class="profile-info">
                <div class="profile-photo">
                    <img src="<?php echo $profilePic; ?>" alt="Profile Photo">
                    <button class="change-photo" id="change-profile"><i class="fas fa-camera"></i></button>
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

        <section id="posts">
            <h2>Posts</h2>
            <div id="post-list">
                <!-- Posts will be here -->
            </div>
        </section>
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
</body>
</html>
