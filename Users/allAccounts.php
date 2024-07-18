<?php

include('../config.php');
include('search.php');


if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['userId'])) {
    $userId = $_GET['userId'];

    // Fetch user data and profile picture in one query
    $query = "SELECT u.*, p.profile_pic 
              FROM users u 
              LEFT JOIN profile p ON u.Id = p.userId 
              WHERE u.Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();

        // If no profile pic, set a default
        if (empty($userData['profile_pic'])) {
            $userData['profile_pic'] = 'path/to/default/profile-image.jpg';
        }

        echo json_encode($userData);
    } else {
        echo json_encode(["error" => "User not found"]);
    }

    $stmt->close();
    $conn->close();
}

// Reset Password
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $userId = $_POST['reset_password'];
    
    
    $sql = "UPDATE users SET password = NULL WHERE Id = ?";
    
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    
    
    if ($stmt->execute()) {
        echo "<script>alert('Password has been reset successfully.');</script>";
    } else {
        echo "<script>alert('Error resetting password: " . $conn->error . "');</script>";
    }
    
    
    $stmt->close();
}



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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

</head>

<!-- style for pop up edit user -->
<style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: white;
        color: maroon;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 20px;
        position: relative;
    }

    .close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 20px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: maroon;
        text-decoration: none;
    }

    .edit-btn {
        background: none;
        border: none;
        color: blue;
        cursor: pointer;
    }

    .cover-photo {
        width: 100%;
        height: 150px;
        background-size: cover;
        background-position: center;
        position: relative;
        margin-bottom: 70px;
        border-radius: 20px;
    }

    .profile-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: #ddd;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 30px;
        overflow: hidden;
        position: absolute;
        bottom: -80px;
        left: 20px;
        border: 3px solid white;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .profile-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #editForm {
        display: grid;
        grid-template-columns: auto 1fr;
        gap: 10px;
        align-items: center;
    }

    #editForm label {
        text-align: right;
        padding-right: 10px;
    }

    #editForm input[type="text"],
    #editForm input[type="email"] {
        width: 100%;
        padding: 5px;
        box-sizing: border-box;
        border-radius: 10px;
        border: 1px solid #ddd;
    }

    #editForm br {
        display: none;
    }

    #editForm button {
        grid-column: 2;
        width: auto;
        padding: 5px 15px;
        margin-top: 10px;
        background-color: maroon;
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        border-radius: 5px;
        margin-top: 10px;
    }

    #editForm button:hover {
        background-color: #600;
    }


    .reset-password {
        color: darkred;
        font-size: 14px;
        cursor: pointer;
        display: block;
        text-align: right;
        margin-top: -45px;
        margin-right: 20px;
        margin-bottom: 30px;
        text-decoration: none;
        transition: color 0.3s, background-color 0.3s, text-decoration 0.3s;
    }

    .reset-password:hover {
        color: maroon;
        background-clip: text;
        text-decoration: underline;
    }

/* All Accounts and Verify Account START*/
#manageAccountModal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

#manageAccountModal .modal-content {
    background-color: maroon !important;
    display: block;
    position: fixed;
    top: 20%;
    left: 50%;
    transform: translate(-50%, -50%);
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    width: 80%;
    max-width: 500px;

}

.modal-content h2 {
    color: white;
    text-align: center;
}


</style>


<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <a href="ADMIN.php"><img src="../images/teknospace-logo.jpg" alt="Tekno Space Logo"></a>
                <span>TEKNOSPACE</span>
            </div>
            <div class="nav-links">
                <!-- <a href="#home" class="icon"><i class="fi fi-ss-user"></i></a> -->
                <a href="../Profile/Profile_Page.php" class="icon"><i class="fi fi-ss-user"></i><span class="nav-link"></span></a>
                <a href="#profile" class="icon"><i class="fi fi-br-bell-notification-social-media"></i></a>
                <!-- <a href="../index.php">Log Out</a> -->
                <a href="#" onclick="showLogoutModal(); return false;">Log Out</a>
            </div>
        </div>
    </header>

    <nav class="nav">
        <ul>
            <li><a href="ADMIN.php" class="icon"><i class="fi fi-ss-megaphone"></i><span class="nav-text">School Updates</span></a></li>
            <li><a href="../Maintenance/adminMaintenance.php" class="icon"><i class="fi fi-br-tools"></i><span class="nav-text">Maintenance</span></a></li>
            <li><a href="../LostAndFound/adminLostFound.php" class="icon"><i class="fi fi-ss-grocery-basket"></i><span class="nav-text">Lost and Found</span></a></li>
            <li>
                <a href="#manageAccount" class="icon manage-account" style="color: #fff3b0; background-color: #8B1818;"><i class="fas fa-user-cog"></i><span class="nav-text">Manage Account</span></a>
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

        <div class="search-container">
            <form id="searchForm" method="GET" action="">
                <div class="search-input-wrapper">
                    <input type="text" id="searchInput" style="width: 97%" name="search" value="<?php if (isset($_GET['searchInput'])) {
                                                                                                    echo $_GET['search'];
                                                                                                } ?>" class="form-control" placeholder="Search by Name, ID, Course, or Email...">
                    <i class="fa fa-search"></i>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        


        <div id="logoutModal" class="logout-modal">
            <div class="logout-modal-content">
                <img src="../images/check_gif.webp" alt="Success" class="logout-icon">
                <p>Logged out successfully</p>
            </div>
        </div>

        <div id="searchResults"></div>
        <div id="searchResults">
            <?php

            if (isset($_GET['search'])) {
                $filtervalues = $_GET['search'];
                $query = "SELECT id, userType, firstName, middleName, lastName, idNumber, course, email 
              FROM users 
              WHERE CONCAT(id, userType, firstName, middleName, lastName, idNumber, course, email) 
              LIKE '%$filtervalues%'";
                $query_run = mysqli_query($connection, $query);

                if (mysqli_num_rows($query_run) > 0) {
                    foreach ($query_run as $items) {
            ?>
                        <tr>
                            <td><?= $items['Id']; ?></td>
                            <td><?= $items['userType']; ?></td>
                            <td><?= $items['firstName']; ?></td>
                            <td><?= $items['middleName']; ?></td>
                            <td><?= $items['lastName']; ?></td>
                            <td><?= $items['idNumber']; ?></td>
                            <td><?= $items['course']; ?></td>
                            <td><?= $items['email']; ?></td>
                        </tr>
                    <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="8">No Record Found</td>
                    </tr>
            <?php
                }
            }
            ?>
        </div>

        <div id="allUsersTable">
            <?php
            include('../config.php');

            if (!isset($_SESSION['valid'])) {
                header("Location: ../login.php");
                exit();
            }

            $userName = $_SESSION['username'];
            $firstName = $_SESSION['firstName'];
            $course = $_SESSION['course'];
            $idNumber = $_SESSION['idNumber'];
            $email = $_SESSION['valid'];



            $sql = "SELECT Id, userType, firstName, middleName, lastName, idNumber, course, email FROM users";
            if ($conn) {
                $result = $conn->query($sql);


                if ($result && $result->num_rows > 0) {

                    echo "<table border='1'>";
                    echo "<tr>";
                    echo "<th>ID</th>";
                    echo "<th>User Type</th>";
                    echo "<th>Name</th>";
                    echo "<th>ID Number</th>";
                    echo "<th>Course</th>";
                    echo "<th>Email</th>";
                    echo "<th>Edit</th>";
                    echo "</tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Id"] . "</td>";
                        echo "<td>" . $row["userType"] . "</td>";
                        echo "<td>" . $row["firstName"] . " " . $row["middleName"] . " " . $row["lastName"] . "</td>";
                        echo "<td>" . $row["idNumber"] . "</td>";
                        echo "<td>" . $row["course"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td><button onclick='openEditModal(" . json_encode($row) . ")' class='edit-btn'><i class='fas fa-edit'></i></button></td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "0 results";
                }

                $result->close();
            } else {
                echo "Database connection is not valid.";
            }

            $conn->close();
            ?>
        </div>

        <!-- EDIT USER -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>

                <h2 style="color: maroon; text-align: center;">Edit User</h2>

                <div id="coverPhoto" class="cover-photo">
                    <div class="profile-photo">
                        <img id="profileImage" src="" alt="Profile Photo">
                    </div>
                </div>
                <span class="reset-password" onclick="confirmResetPassword()">Reset Password</span>

                <form id="editForm">
                    <input type="hidden" id="editId">
                    <label for="editUserType">User Type:</label>
                    <input type="text" id="editUserType"><br>
                    <label for="editFirstName">First Name:</label>
                    <input type="text" id="editFirstName"><br>
                    <label for="editMiddleName">Middle Name:</label>
                    <input type="text" id="editMiddleName"><br>
                    <label for="editLastName">Last Name:</label>
                    <input type="text" id="editLastName"><br>
                    <label for="editIdNumber">ID Number:</label>
                    <input type="text" id="editIdNumber"><br>
                    <label for="editCourse">Course:</label>
                    <input type="text" id="editCourse"><br>
                    <label for="editEmail">Email:</label>
                    <input type="email" id="editEmail"><br>
                    <button type="button" onclick="saveChanges()">Save Changes</button>
                </form>

            </div>
        </div>


    </main>


    <script src="Admin_Homepage.js"></script>
    <script src="admin.js"></script>
    <!-- Manage Accounts -->
    <script>
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

        window.addEventListener('click', function(event) {
            if (event.target == postModal) {
                postModal.style.display = "none";
            }
        });
    </script>


    <!-- SEARCH -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchInput = document.getElementById('searchInput');
            const searchResults = document.getElementById('searchResults');
            const allUsersTable = document.getElementById('allUsersTable');

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const searchTerm = searchInput.value.trim();

                if (searchTerm !== '') {

                    allUsersTable.style.display = 'none';

                    fetch('search.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'search=' + encodeURIComponent(searchTerm)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                displayResults(data.data);
                            } else if (data.status === 'no_results') {
                                searchResults.innerHTML = '<p>No results found.</p>';
                            } else {
                                searchResults.innerHTML = '<p>An error occurred while searching.</p>';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            searchResults.innerHTML = '<p>An error occurred while searching.</p>';
                        });
                } else {
                    searchResults.innerHTML = '<p>Please enter a search term.</p>';
                    allUsersTable.style.display = 'block';
                }
            });

            // show all users
            searchInput.addEventListener('input', function() {
                if (this.value.trim() === '') {
                    allUsersTable.style.display = 'block';
                    searchResults.innerHTML = '';
                }
            });

            function displayResults(results) {
                let tableHtml = `
            <table border='1'>
                <tr>
                    <th>ID</th>
                    <th>User Type</th>
                    <th>Name</th>
                    <th>ID Number</th>
                    <th>Course</th>
                    <th>Email</th>
                </tr>
        `;

                results.forEach(row => {
                    tableHtml += `
                <tr>
                    <td>${row.id}</td>
                    <td>${row.userType}</td>
                    <td>${row.firstName} ${row.middleName} ${row.lastName}</td>
                    <td>${row.idNumber}</td>
                    <td>${row.course}</td>
                    <td>${row.email}</td>
                </tr>
            `;
                });

                tableHtml += '</table>';
                searchResults.innerHTML = tableHtml;
            }
        });
    </script>

    <!-- EDIT USER POP UP -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('click', function(e) {
                if (e.target && e.target.classList.contains('edit-icon')) {
                    const userId = e.target.getAttribute('data-id');
                    openEditPopup(userId);
                }
            });

            function openEditPopup(userId) {
                // Create the popup
                const popup = document.createElement('div');
                popup.className = 'edit-popup';
                popup.innerHTML = `
            <div class="edit-popup-content">
                <h2>Edit User ID: ${userId}</h2>
                <p>Edit form goes here...</p>
                <button onclick="closeEditPopup()">Close</button>
            </div>
        `;

                // Add the popup to the body
                document.body.appendChild(popup);

                // Style for the popup
                const style = document.createElement('style');
                style.textContent = `
            .edit-popup {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
            }
            .edit-popup-content {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }
        `;
                document.head.appendChild(style);
            }


            window.closeEditPopup = function() {
                const popup = document.querySelector('.edit-popup');
                if (popup) {
                    popup.remove();
                }
            };
        });
    </script>

    <!-- for editting user accounts -->
    <script>
        var modalEdit = document.getElementById("editModal");

        function openEditModal(userData) {
            document.getElementById("editId").value = userData.Id;
            document.getElementById("editUserType").value = userData.userType;
            document.getElementById("editFirstName").value = userData.firstName;
            document.getElementById("editMiddleName").value = userData.middleName;
            document.getElementById("editLastName").value = userData.lastName;
            document.getElementById("editIdNumber").value = userData.idNumber;
            document.getElementById("editCourse").value = userData.course;
            document.getElementById("editEmail").value = userData.email;

            // Retrieve and display the profile image
            const editId = userData.Id;
            fetchProfileImage(editId);

            modalEdit.style.display = "block";
        }

        function fetchProfileImage(userId) {
            fetch(`../Profile/get_profile_image.php?userId=${userId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.profile_pic) {
                        document.getElementById("profileImage").src = data.profile_pic;
                    } else {
                        // Set a default image if no profile picture is found
                        document.getElementById("profileImage").src = "../images/profile-icon.png";
                    }

                    if (data.cover_photo) {
                        console.log('Setting cover photo:', data.cover_photo);
                        coverPhoto.style.backgroundImage = `url('${data.cover_photo}')`;
                    } else {
                        console.log('No cover photo found, using default');
                        coverPhoto.style.backgroundImage = "url('https://www.rappler.com/tachyon/2021/09/cit-campus-20210916.png?resize=850%2C315&zoom=1')";
                    }
                })
                .catch(error => {
                    console.error('Error fetching profile image:', error);
                    // default image
                    document.getElementById("profileImage").src = "../images/profile-icon.png";
                    document.getElementById("coverPhoto").style.backgroundImage = "url('../images/Background.png')";
                });
        }

        function closeModal() {
            modalEdit.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modalEdit) {
                closeModal();
            }
        }

        function saveChanges() {
            var updatedData = {
                Id: document.getElementById("editId").value,
                userType: document.getElementById("editUserType").value,
                firstName: document.getElementById("editFirstName").value,
                middleName: document.getElementById("editMiddleName").value,
                lastName: document.getElementById("editLastName").value,
                idNumber: document.getElementById("editIdNumber").value,
                course: document.getElementById("editCourse").value,
                email: document.getElementById("editEmail").value
            };

            fetch('update_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(updatedData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("User updated successfully");
                        closeModal();
                        //for refresh
                        location.reload();
                    } else {
                        alert("Error updating user: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert("An error occurred while updating the user");
                });
        }
    </script>


    <script>
        function openModal() {
            document.getElementById('editModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function confirmResetPassword() {
            if (confirm('Are you sure you want to reset the password of this user?')) {
                const userId = document.getElementById("editId").value;

                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = ''; // Submit to the current page

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'reset_password';
                input.value = userId;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
    
    <style>
        /* LOGOUT MODAL */
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
            font-family: "Outfit", sans-serif;
            text-decoration: bold;
        }

        .logout-icon {
            width: 30px;
            height: 30px;
            margin-right: 10px;
        }
    </style>
</body>

</html>