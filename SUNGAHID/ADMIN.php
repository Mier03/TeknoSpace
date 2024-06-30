<?php
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

$userName = $_SESSION['username'];
$firstName = $_SESSION['firstName'];
$course = $_SESSION['course'];
$idNumber = $_SESSION['idNumber'];
$email = $_SESSION['valid'];


// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $sql = "SELECT Id, userType, firstName, middleName, lastName, idNumber, course, email FROM users";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     echo "<table>";
//     echo "<tr><th>Id</th><th>User Type</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>ID Number</th><th>Course</th><th>Email</th></tr>";
//     while($row = $result->fetch_assoc()) {
//         echo "<tr><td>" . $row["Id"]. "</td><td>" . $row["userType"]. "</td><td>" . $row["firstName"]. "</td><td>" . $row["middleName"]. "</td><td>" . $row["lastName"]. "</td><td>" . $row["idNumber"]. "</td><td>" . $row["course"]. "</td><td>" . $row["email"]. "</td></tr>";
//     }
//     echo "</table>";
// } else {
//     echo "0 results";
// }

// $conn->close();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <textarea placeholder="<?php echo "What's on your mind, " . htmlspecialchars($firstName) . "?"; ?>"></textarea>
                <div class="post-options">
                    <p>Add to your post</p>
                    <div class="option-icons">
                        <i class="fi fi-br-picture"></i>
                    </div>
                </div>
                <button class="btn-post">Post</button>
            </div>
        </div>


    </main>
    <script src="Admin_Homepage.js"></script>
    <script>
    
    
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

        const postModal = document.getElementById("postModal");
        const postModalCloseBtn = document.querySelector('#postModal .close');

        function openPostModal() {
            postModal.style.display = "block";
        }

        function closePostModal() {
            postModal.style.display = "none"; 
        }

        postModalCloseBtn.addEventListener('click', closePostModal);

        window.addEventListener('click', function (event) {
            if (event.target == postModal) {
                postModal.style.display = "none"; 
            }
        });
</script>
</body>

</html>