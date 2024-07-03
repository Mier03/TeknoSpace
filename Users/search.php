<?php


session_start();
include('../config.php');

if (!isset($_SESSION['valid'])) {
    echo json_encode(['error' => 'Not authorized']);
    exit();
}

if (isset($_POST['search'])) {
    $filtervalues = mysqli_real_escape_string($conn, $_POST['search']);
    $query = "SELECT id, userType, firstName, middleName, lastName, idNumber, course, email
          FROM users
          WHERE CONCAT(id, userType, firstName, middleName, lastName, idNumber, course, email) 
          LIKE '%$filtervalues%'";
    $query_run = mysqli_query($conn, $query);

    $results = [];
    if (mysqli_num_rows($query_run) > 0) {
        while ($row = mysqli_fetch_assoc($query_run)) {
            $results[] = $row;
        }
        echo json_encode(['status' => 'success', 'data' => $results]);
    } else {
        echo json_encode(['status' => 'no_results']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No search term provided']);
}

?>