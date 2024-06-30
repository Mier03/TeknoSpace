<?php

include('../config.php');
include('search.php');


if (!isset($_SESSION['valid'])) {
    header("Location: ../login.php");
    exit();
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

<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <a href="ADMIN.php"><img src="../images/teknospace-logo.jpg" alt="Tekno Space Logo"></a>
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

        <div class="search-container">
            <form id="searchForm" method="GET" action="">
                <div class="search-input-wrapper">
                    <input type="text" id="searchInput" name="search" value="<?php if (isset($_GET['searchInput'])) {
                                                                                                        echo $_GET['search'];
                                                                                                    } ?>" class="form-control" placeholder="Search by Name, ID, Course, or Email...">
                    <i class="fa fa-search"></i>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
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
                    echo "</tr>";

                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["Id"] . "</td>";
                        echo "<td>" . $row["userType"] . "</td>";
                        echo "<td>" . $row["firstName"] . " " . $row["middleName"] . " " . $row["lastName"] . "</td>";
                        echo "<td>" . $row["idNumber"] . "</td>";
                        echo "<td>" . $row["course"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
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

    </main>


    <script src="Admin_Homepage.js"></script>
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

</body>

</html>