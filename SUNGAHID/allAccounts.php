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

        <div class="search-container">
            <form id="searchForm" method="GET" action="">
                <input type="text" id="searchInput" name="search" value="<?php if (isset($_GET['searchInput'])) {
                                                                                echo $_GET['search'];
                                                                            } ?>" class="form-control" placeholder="Search by Name, ID, Course, or Email...">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>

        <div id="searchResults">
            <?php

            session_start();

            include('../config.php');

            if (!isset($_SESSION['valid'])) {
                header("Location: ../login.php");
                exit();
            }


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
                            <td><?= $items['id']; ?></td>
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
        const searchForm = document.getElementById('searchForm');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = searchInput.value.trim();

            if (searchTerm !== '') {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'allAccounts.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {

                        searchResults.innerHTML = xhr.responseText;
                    }
                };
                xhr.send('search=' + encodeURIComponent(searchTerm));
            } else {
                searchResults.innerHTML = '<p>Please enter a search term.</p>';
            }
        });
    </script>

</body>

</html>